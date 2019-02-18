<?php

namespace App\Http\Controllers;

use App\InviteDentistsReward;
use App\PublicKey;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Dompdf\Dompdf;

class PatientController extends Controller {
    public function getNotLoggedView()   {
        return view('pages/patient', ['clinics' => (new APIRequestsController())->getAllClinicsByName()]);
    }

    protected function getInviteDentistsView() {
        return view('pages/logged-user/patient/invite-dentists', ['invited_dentists_list' => InviteDentistsReward::where(array('patient_id' => session('logged_user')['id']))->get()->sortByDesc('created_at')->all()]);
    }

    protected function getCongratulationsView($slug) {
        return view('pages/logged-user/patient/congratulations', ['contract' => TemporallyContract::where(array('slug' => $slug))->get()->first()]);
    }

    protected function getPatientContractView($slug) {
        return view('pages/logged-user/patient/patient-contract-view', ['contract' => TemporallyContract::where(array('slug' => $slug))->get()->first()]);
    }

    public function getPatientAccess()    {
        if((new UserController())->checkSession()) {
            if(filter_var(session('logged_user')['have_contracts'], FILTER_VALIDATE_BOOLEAN)) {
                return view('pages/logged-user/patient/have-contracts', ['contracts' => TemporallyContract::where(array('patient_email' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->email))->get()->all(), 'clinics' => (new APIRequestsController())->getAllClinicsByName()]);
            } else {
                //IF PATIENT HAVE NO EXISTING CONTRACTS
                return view('pages/logged-user/patient/start-first-contract', ['clinics' => (new APIRequestsController())->getAllClinicsByName()]);
            }
        }else {
            return (new HomeController())->getView();
        }
    }

    protected function authenticate(Request $request) {
        $this->validate($request, [
            'token' => 'required',
            'id' => 'required'
        ], [
            'token.required' => 'Token is required.',
            'id.required' => 'Email is required.'
        ]);

        $session_arr = [
            'token' => $request->input('token'),
            'id' => $request->input('id'),
            'type' => 'patient',
            'have_contracts' => false
        ];

        //check if there is temporallycontract for this patient by email or by user_id (we fill user_id for this temporally contract once patient register - this is in case patient change his email while he still have the proposal running)
        if(filter_var($request->input('have_contracts'), FILTER_VALIDATE_BOOLEAN) || TemporallyContract::where(array('patient_email' => $request->input('email')))->get()->all()) {
            $session_arr['have_contracts'] = true;
        }

        session(['logged_user' => $session_arr]);
        return redirect()->route('patient-access');
    }

    protected function getInviteDentistsPopup(Request $request) {
        $data = $request->input('serialized');
        parse_str($data, $postdata);

        //===================================================================================
        //CHECK HERE IF THIS DENTIST EMAIL IS NOT ALREADY REGISTERED IN LOCAL DB AND IN API
        //===================================================================================

        $view = view('partials/invite-dentists-popup', ['data' => $postdata]);
        $view = $view->render();
        return response()->json(['success' => $view]);
    }

    protected function inviteDentists(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'dentist-name' => 'required',
            'website' => 'required',
            'email' => 'required',
            'redirect' => 'required',
        ], [
            'title.required' => 'Title is required.',
            'dentist-name.required' => 'Dentist name is required.',
            'website.required' => 'Website is required.',
            'email.required' => 'Email is required.',
            'redirect.required' => 'Redirect is required.',
        ]);

        $data = $this->clearPostData($request->input());

        //check email validation
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->route($data['redirect'])->with(['error' => 'Your form was not sent. Please try again with valid email.']);
        }

        //if user entered dcn_address for first time save it in coredb
        if(!empty($data['dcn_address'])) {
            $post_fields_arr = array('dcn_address' => $data['dcn_address']);

            //handle the API response
            $api_response = (new APIRequestsController())->updateUserData($post_fields_arr);
            if(!$api_response) {
                return redirect()->route($data['redirect'])->with(['errors_response' => $api_response['errors']]);
            }
        }

        //===================================================================================
        //CHECK HERE IF THIS DENTIST EMAIL IS NOT ALREADY REGISTERED IN LOCAL DB AND IN API
        //===================================================================================

        $sender = (new APIRequestsController())->getUserData(session('logged_user')['id']);

        $body = '<!DOCTYPE html><html><head></head><body><div style="font-size: 16px;">Dear '.$data['title'].' '.$data['dentist-name'].',<br><br><br>My name is <b>'.$sender->name.'</b> and I as a patient of yours I would like to invite you to join <b>Dentacoin Assurance</b> - the first blockchain* dental assurance that entitles patients to preventive dental care against affordable monthly premiums in Dentacoin (DCN) currency.<br><br>It’s very easy to start: Just sign up, wait for approval and create your first contract. <a href="https://assurance.dentacoin.com/" style="color: #126585;font-weight: bold; text-decoration: none;" target="_blank">See how it works.</a> After/ if I agree to the conditions offered, we will get into a trustful agreement benefiting from an automated payment & notification system.<br><br>Affordable, preventive care for me - regular income and loyal patients for you!<br><br><br><a href="https://assurance.dentacoin.com/support-guide" style="font-size: 20px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">LEARN MORE</a><br><br><br>Looking forward to seeing you onboard! If you need any further information, do not hesitate to contact the Dentacoin Assurance team at <a href="mailto:assurance@dentacoin.com" style="color: #126585;font-weight: bold; text-decoration: none;">assurance@dentacoin.com</a>.<br><br>Regards,<br><b>'.$sender->name.'</b><br><br><br><i style="font-size: 13px;">* Blockchain is just a new technology used for secure storage and exchange of value and data.</i></div></body></html>';

        Mail::send(array(), array(), function($message) use ($body, $data, $sender) {
            $message->to($data['email'])->subject('Your patient '.$sender->name.' invited you to join Dentacoin Assurance');
            $message->from($sender->email, $sender->name)->replyTo($sender->email, $sender->name);
            $message->setBody($body, 'text/html');
        });

        if(count(Mail::failures()) > 0) {
            return redirect()->route($data['redirect'])->with(['error' => 'Email has not been sent to your dentist, please try again later.']);
        } else {
            $invite_dentist_reward = new InviteDentistsReward();
            $invite_dentist_reward->patient_id = session('logged_user')['id'];
            $invite_dentist_reward->dentist_email = $data['email'];
            $invite_dentist_reward->title = $data['title'];
            $invite_dentist_reward->name = $data['dentist-name'];
            $invite_dentist_reward->website = $data['website'];
            if(!empty($data['phone'])) {
                $invite_dentist_reward->phone = $data['phone'];
            }

            //saving to DB
            $invite_dentist_reward->save();
            return redirect()->route($data['redirect'])->with(['success' => 'Email has been sent to your dentist successfully.']);
        }
    }

    protected function getContractProposal($slug) {
        $contract = TemporallyContract::where(array('slug' => $slug))->get()->first();
        if((new UserController())->checkDentistSession() || empty($contract) || ((new UserController())->checkPatientSession() && $contract->patient_email != (new APIRequestsController())->getUserData(session('logged_user')['id'])->email)) {
            //if dentist trying to access the proposal or if there is no such contract or if different patient trying to access the proposal
            return abort(404);
        } else if((new UserController())->checkPatientSession() && $contract->patient_email == (new APIRequestsController())->getUserData(session('logged_user')['id'])->email) {
            $params = array(
                'contract' => $contract,
                'countries' => (new APIRequestsController())->getAllCountries(),
                'shown' => 'one'
            );

            if(!empty($contract->patient_id_number)) {
                $params['shown'] = 'two';
            }
            //if patient is logged in and if the contract is about the logged patient
            return view('pages/logged-user/patient/review-assurance-contract', $params);
        } else {
            return view('pages/contract-proposal-partly', ['contract' => $contract]);
        }
    }

    protected function updateAndSignContract(Request $request) {
        $logged_patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        $required_fields_arr = array(
            'patient_signature' => 'required',
            'patient-id-number' => 'required|max:20',
            'contract' => 'required',
        );
        $required_fields_msgs_arr = array(
            'patient_signature.required' => 'Patient signature is required.',
            'patient-id-number.required' => 'Patient ID number signature is required.',
            'contract.required' => 'Contract slug is required.',
        );

        if(empty($logged_patient->dcn_address)) {
            $required_fields_arr['dcn_address'] = 'required|max:42';
            $required_fields_msgs_arr['dcn_address.required'] = 'Wallet Address is required';
        }

        if(empty($logged_patient->country_id)) {
            $required_fields_arr['country'] = 'required';
            $required_fields_msgs_arr['country.required'] = 'Country is required';
        }

        $data = $this->clearPostData($request->input());
        $contract = TemporallyContract::where(array('slug' => $data['contract']))->get()->first();

        //getting the public key for this address stored in the assurance db (this table is getting updated by wallet.dentacoin.com)
        $patient_pub_key = PublicKey::where(array('address' => $logged_patient->dcn_address))->get()->first();
        $dentist_pub_key = PublicKey::where(array('address' => (new APIRequestsController())->getUserData($contract->dentist_id)->dcn_address))->get()->first();
        if(empty($patient_pub_key) || empty($dentist_pub_key)) {
            return redirect()->route('patient-access', ['slug' => $data['contract']])->with(['error' => 'No such public keys in the database.']);
        }

        if(empty($contract) || (!empty($contract) && $contract->patient_email != $logged_patient->email)) {
            //if user trying to fake the contract slug
            return abort(404);
        }

        //update CoreDB api data for this patient
        if(isset($data['country']) || isset($data['dcn_address'])) {
            $curl_arr = array();
            if(isset($data['country'])) {
                if(!empty($data['country'])) {
                    $curl_arr['country_code'] = $data['country'];
                }else {
                    return redirect()->route('patient-access', ['slug' => $data['contract']])->with(['error' => 'Country is required']);
                }
            }
            if(isset($data['dcn_address'])) {
                if(!empty($data['dcn_address'])) {
                    $curl_arr['dcn_address'] = $data['dcn_address'];
                }else {
                    return redirect()->route('patient-access', ['slug' => $data['contract']])->with(['error' => 'Wallet Address is required']);
                }
            }

            //handle the API response
            $api_response = (new APIRequestsController())->updateUserData($curl_arr);
            if(!$api_response) {
                return redirect()->route('patient-access', ['slug' => $data['contract']])->with(['errors_response' => $api_response['errors']]);
            }
        }

        //create image from the base64 signature
        $signature_filename = 'patient-signature.png';
        $temp_contract_folder_path = CONTRACTS . $data['contract'];
        file_put_contents($temp_contract_folder_path . DS . $signature_filename, $this->base64ToPng($data['patient_signature']));

        $contract->patient_id = $logged_patient->id;
        $contract->patient_id_number = $data['patient-id-number'];
        $contract->patient_sign = true;
        $contract->contract_active_at = new \DateTime();

        //GENERATE PDF
        $view_start = view('partials/pdf-contract-layout-start');
        $html_start = $view_start->render();

        $view_body = view('partials/pdf-contract-body');
        $html_body = $view_body->render();

        $view_end = view('partials/pdf-contract-layout-end');
        $html_end = $view_end->render();

        //sending the pdf html to encryption nodejs api
        $encrypted_html_by_patient = (new \App\Http\Controllers\APIRequestsController())->encryptFile($patient_pub_key->public_key, $this->minifyHtmlParts($html_body));
        $encrypted_html_by_dentist = (new \App\Http\Controllers\APIRequestsController())->encryptFile($dentist_pub_key->public_key, $this->minifyHtmlParts($html_body));

        //if no errors from the api
        if($encrypted_html_by_patient && !isset($encrypted_html_by_patient->error) && $encrypted_html_by_dentist && !isset($encrypted_html_by_dentist->error)) {
            $this->storePdfFileTemporally($html_start, $encrypted_html_by_patient->response_obj->success->encrypted, $html_end, CONTRACTS . $contract->slug . DS . 'patient-pdf-file.pdf');
            $this->storePdfFileTemporally($html_start, $encrypted_html_by_dentist->response_obj->success->encrypted, $html_end, CONTRACTS . $contract->slug . DS . 'dentist-pdf-file.pdf');

            //creating zip file with the both encrypted pdfs
            $contract_folder_relative_path = 'assets' . DS . 'contracts' . DS . $contract->slug . DS;
            $zipper = new \Chumper\Zipper\Zipper;
            $zip_name = 'assurance-contracts.zip';
            $zipper->make($contract_folder_relative_path . $zip_name)->add([$contract_folder_relative_path . 'dentist-pdf-file.pdf', $contract_folder_relative_path . 'patient-pdf-file.pdf']);
            $zipper->close();

            $ipfs_hash = (new \App\Http\Controllers\APIRequestsController())->uploadFileToIPFS(CONTRACTS . DS . $contract->slug . DS . $zip_name);
            if($ipfs_hash->response_obj && $ipfs_hash->response_obj->success) {
                $contract->document_hash = $ipfs_hash->response_obj->success->hash;

                //deleting the contract folder
                unlink(CONTRACTS . DS . $contract->slug);

                //updating the status to awaiting-payment
                $contract->status = 'awaiting-payment';
                $contract->save();
            } else {
                return redirect()->route('patient-access', ['slug' => $data['contract']])->with(['error' => 'IPFS uploading is not working at the moment, please try to sign this contract later again.']);
            }
        } else {
            return redirect()->route('patient-access', ['slug' => $data['contract']])->with(['error' => 'IPFS uploading is not working at the moment, please try to sign this contract later again.']);
        }
        return redirect()->route('congratulations', ['slug' => $data['contract']]);
    }

    protected function storePdfFileTemporally($html_start, $html_body, $html_end, $pdf_file_path) {
        $dompdf = new DOMPDF();
        $dompdf->load_html($html_start . '<div style="word-wrap: break-word;">'. $html_body . '</div>' . $html_end);
        $dompdf->render();
        $pdf_file = $dompdf->output();

        //saving the pdf file to the contracts folder, but this will be temporally
        file_put_contents($pdf_file_path, $pdf_file);
    }
}
