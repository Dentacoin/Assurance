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

    public function renderTestContract() {
        return view('pages/test-view-contract', ['contract' => TemporallyContract::where(array('id' => 8))->get()->first()]);
    }

    protected function getCongratulationsView($slug) {
        $contract = TemporallyContract::where(array('slug' => $slug, 'status' => 'awaiting-payment'))->get()->first();
        if(!empty($contract)) {
            return view('pages/logged-user/patient/congratulations', ['contract' => $contract, 'dcn_for_one_usd' => $this->getIndacoinPricesInUSD('DCN'), 'eth_for_one_usd' => $this->getIndacoinPricesInUSD('ETH')]);
        } else {
            return abort(404);
        }
    }

    protected function getPatientContractView($slug) {
        $contract = TemporallyContract::where(array('slug' => $slug))->get()->first();
        if($contract->status == 'pending') {
            return abort(404);
        } else {
            return view('pages/logged-user/patient/patient-contract-view', ['contract' => $contract, 'dcn_for_one_usd' => $this->getIndacoinPricesInUSD('DCN'), 'eth_for_one_usd' => $this->getIndacoinPricesInUSD('ETH')]);
        }
    }

    protected function getReconsiderMonthlyPremium(Request $request) {
        $contract = TemporallyContract::where(array('slug' => $request->input('contract'), 'status' => 'pending'))->get()->first();
        if($contract && (new APIRequestsController())->getUserData(session('logged_user')['id'])->email == $contract->patient_email) {
            $view = view('partials/reconsider-monthly-premium', ['dentist' => (new APIRequestsController())->getUserData($contract->dentist_id), 'contract' => $contract]);
            $view = $view->render();
            return response()->json(['success' => $view]);
        } else {
            return response()->json(['error' => 'Wrong data passed.']);
        }
    }

    public function getPatientAccess()    {
        if((new UserController())->checkPatientSession()) {
            $logged_patient_email = (new APIRequestsController())->getUserData(session('logged_user')['id'])->email;
            if(TemporallyContract::where(array('patient_email' => $logged_patient_email))->get()->all()) {
                return view('pages/logged-user/patient/have-contracts', ['contracts' => TemporallyContract::where(array('patient_email' => $logged_patient_email))->get()->sortByDesc('created_at'), 'clinics' => (new APIRequestsController())->getAllClinicsByName()]);
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
            'type' => 'patient'
        ];

        $current_logging_patient = (new APIRequestsController())->getUserData($request->input('id'));
        if($current_logging_patient->self_deleted != NULL) {
            return redirect()->route('home')->with(['error' => 'This account is deleted, you cannot log in with this account anymore.']);
        } else {
            session(['logged_user' => $session_arr]);

            $rewards = InviteDentistsReward::where(array('patient_id' => $request->input('id'), 'dentist_registered_and_approved' => 1, 'sent_to_api' => 0, 'payed_on' => NULL))->get()->all();
            //if rewards forward them to coredb
            if(!empty($rewards)) {
                foreach($rewards as $reward) {
                    $data = array(
                        'amount' => self::DCN_REWARD,
                        'type' => 'assurance',
                        'reference_id' => $reward->id
                    );
                    $reward_api_method_response = (new APIRequestsController())->registerDCNReward($data);
                    if($reward_api_method_response->success) {
                        $reward->sent_to_api = 1;
                        $reward->save();
                    }


                }
            }

            //send request to API to add this reward to the patient account
            if(!empty($request->input('route')) && !empty($request->input('slug'))) {
                return redirect()->route($request->input('route'), ['slug' => $request->input('slug')]);
            } else {
                return redirect()->route('patient-access');
            }
        }
    }

    protected function getInviteDentistsPopup(Request $request) {
        $data = $request->input('serialized');
        parse_str($data, $postdata);

        $current_patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']);
        $receiver = $postdata['title'] . ' ' . $postdata['dentist-name'] . ' (' . $postdata['email'] . ')';
        $body = '<div class="padding-bottom-25 padding-top-15">My name is <span class="calibri-bold">'.$current_patient->name.'</span> and I as a patient of yours I would like to invite you to join <span class="calibri-bold">Dentacoin Assurance</span> - the first blockchain* dental assurance that entitles patients to preventive dental care against affordable monthly premiums in Dentacoin (DCN) currency.</div><div class="padding-bottom-25">It’s very easy to start: Just sign up, wait for approval and create your first contract. <a href="'.BASE_URL.'" target="_blank" class="blue-green-color calibri-bold">See how it works.</a> After/ if I agree to the conditions offered, we will get into a trustful agreement benefiting from an automated payment & notification system.</div><div class="padding-bottom-20">Affordable, preventive care for me - regular income and loyal patients for you!</div><div class="padding-bottom-20"><a href="{{BASE_URL}}support-guide" target="_blank" class="blue-green-white-btn">LEARN MORE</a></div><div class="padding-bottom-30">Looking forward to seeing you onboard! If you need any further information, do not hesitate to contact the Dentacoin Assurance team at <a href="mailto:assurance@dentacoin.com" class="blue-green-color calibri-bold">assurance@dentacoin.com</a>.</div>';

        $view = view('partials/before-sending-email-confirmation-popup', ['sender' => $current_patient, 'receiver' => $receiver, 'mail_title' => 'Invite Your Dentist', 'mail_subject' => 'Invitation to join Dentacoin Assurance', 'mail_body' => $body]);
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

        //check if someone inviting already invited dentist
        if(InviteDentistsReward::where(array('dentist_email' => $data['email']))->get()->first()) {
            return redirect()->route($data['redirect'])->with(['error' => 'This dentist is already invited in Assurance platform.']);
        }

        //check if the invited dentist is already registered in the CoreDB
        $api_response = (new APIRequestsController())->checkIfFreeEmail($data['email']);
        if(!$api_response->success) {
            return redirect()->route($data['redirect'])->with(['error' => 'This dentist is already registered in Dentacoin tools.']);
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

        $body = '<!DOCTYPE html><html><head></head><body><div style="font-size: 16px;">Dear '.$data['title'].' '.$data['dentist-name'].',<br><br><br>My name is <b>'.$sender->name.'</b> and I as a patient of yours I would like to invite you to join <b>Dentacoin Assurance</b> - the first blockchain* dental assurance that entitles patients to preventive dental care against affordable monthly premiums in Dentacoin (DCN) currency.<br><br>It’s very easy to start: Just sign up, wait for approval and create your first contract. <a href="'.BASE_URL.'" style="color: #126585;font-weight: bold; text-decoration: none;" target="_blank">See how it works.</a> After/ if I agree to the conditions offered, we will get into a trustful agreement benefiting from an automated payment & notification system.<br><br>Affordable, preventive care for me - regular income and loyal patients for you!<br><br><br><a href="'.BASE_URL.'support-guide" style="font-size: 20px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">LEARN MORE</a><br><br><br>Looking forward to seeing you onboard! If you need any further information, do not hesitate to contact the Dentacoin Assurance team at <a href="mailto:assurance@dentacoin.com" style="color: #126585;font-weight: bold; text-decoration: none;">assurance@dentacoin.com</a>.<br><br>Regards,<br><b>'.$sender->name.'</b><br><br><br><i style="font-size: 13px;">* Blockchain is just a new technology used for secure storage and exchange of value and data.</i></div></body></html>';

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
            return redirect()->route($data['redirect'])->with(['success' => 'Invitation has been sent to your dentist successfully. Once he register you will receive 20000 Dentacoins reward.']);
        }
    }

    protected function getContractProposal($slug) {
        $contract = TemporallyContract::where(array('slug' => $slug, 'status' => 'pending'))->get()->first();
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

        if(empty($logged_patient->address)) {
            $required_fields_arr['address'] = 'required';
            $required_fields_msgs_arr['address.required'] = 'Postal Address is required';
        }

        $this->validate($request, $required_fields_arr, $required_fields_msgs_arr);

        $data = $this->clearPostData($request->input());
        $contract = TemporallyContract::where(array('slug' => $data['contract']))->get()->first();

        //if user trying to fake the contract slug
        if(empty($contract) || (!empty($contract) && $contract->patient_email != $logged_patient->email)) {
            return abort(404);
        }


        //check if contract expired
        if((time() - strtotime($contract->created_at)) / (60 * 60 * 24) > DAYS_ACTIVE_CONTRACT_PROPOSAL) {
            return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'This contract proposal has expired.']);
        }

        //update CoreDB api data for this patient
        if(isset($data['country']) || isset($data['dcn_address']) || isset($data['address'])) {
            $curl_arr = array();
            if(isset($data['country'])) {
                if(!empty($data['country'])) {
                    $curl_arr['country_code'] = $data['country'];
                }else {
                    return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'Country is required']);
                }
            }
            if(isset($data['address'])) {
                if(!empty($data['address'])) {
                    $curl_arr['address'] = $data['address'];
                }else {
                    return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'Postal Address is required']);
                }
            }
            if(isset($data['dcn_address'])) {
                if(!empty($data['dcn_address'])) {
                    $curl_arr['dcn_address'] = $data['dcn_address'];
                }else {
                    return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'Wallet Address is required']);
                }
            }

            //handle the API response
            $api_response = (new APIRequestsController())->updateUserData($curl_arr);
            if(!$api_response) {
                return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['errors_response' => $api_response['errors']]);
            }
        }

        //getting the public key for this address stored in the assurance db (this table is getting updated by wallet.dentacoin.com)
        $patient_pub_key = PublicKey::where(array('address' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->dcn_address))->get()->first();
        $dentist_pub_key = PublicKey::where(array('address' => (new APIRequestsController())->getUserData($contract->dentist_id)->dcn_address))->get()->first();

        if(empty($patient_pub_key) || empty($dentist_pub_key)) {
            return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'No such public keys in the database.']);
        }

        //create image from the base64 signature
        $signature_filename = 'patient-signature.png';
        $temp_contract_folder_path = CONTRACTS . $data['contract'];
        file_put_contents($temp_contract_folder_path . DS . $signature_filename, $this->base64ToPng($data['patient_signature']));

        $contract->patient_id = $logged_patient->id;
        $contract->patient_id_number = $data['patient-id-number'];
        $contract->contract_active_at = new \DateTime();

        //GENERATE PDF
        $view_start = view('partials/pdf-contract-layout-start');
        $html_start = $view_start->render();

        $view_body = view('partials/pdf-contract-body', ['contract' => $contract, 'countries' => (new APIRequestsController())->getAllCountries()]);
        //$view_body = view('partials/test-pdf-contract-body', ['contract' => $contract, 'countries' => (new APIRequestsController())->getAllCountries()]);
        $html_body = $view_body->render();

        $view_end = view('partials/pdf-contract-layout-end');
        $html_end = $view_end->render();

        //sending the pdf html to encryption nodejs api
        $encrypted_html_by_patient = (new \App\Http\Controllers\APIRequestsController())->encryptFile($patient_pub_key->public_key, htmlentities($html_body));
        $encrypted_html_by_dentist = (new \App\Http\Controllers\APIRequestsController())->encryptFile($dentist_pub_key->public_key, htmlentities($html_body));

        //if no errors from the api
        if($encrypted_html_by_patient && !isset($encrypted_html_by_patient->error) && $encrypted_html_by_dentist && !isset($encrypted_html_by_dentist->error)) {
            $this->storePdfFileTemporally($html_start, $encrypted_html_by_patient->success->encrypted, $html_end, CONTRACTS . $contract->slug . DS . 'patient-pdf-file.pdf');
            $this->storePdfFileTemporally($html_start, $encrypted_html_by_dentist->success->encrypted, $html_end, CONTRACTS . $contract->slug . DS . 'dentist-pdf-file.pdf');

            //creating zip file with the both encrypted pdfs
            $contract_folder_relative_path = 'assets' . DS . 'contracts' . DS . $contract->slug . DS;
            $zipper = new \Chumper\Zipper\Zipper;
            $zip_name = 'assurance-contracts.zip';
            $zipper->make($contract_folder_relative_path . $zip_name)->add([$contract_folder_relative_path . 'dentist-pdf-file.pdf', $contract_folder_relative_path . 'patient-pdf-file.pdf']);
            $zipper->close();

            $ipfs_hash = (new \App\Http\Controllers\APIRequestsController())->uploadFileToIPFS(CONTRACTS . $contract->slug . DS . $zip_name);

            if($ipfs_hash->response_obj && $ipfs_hash->response_obj->success) {
                $contract->document_hash = $ipfs_hash->response_obj->success->hash;

                //deleting the contract folder
                /*array_map('unlink', glob(CONTRACTS . $contract->slug . '/*.*'));
                rmdir(CONTRACTS . $contract->slug);*/

                //updating the status to awaiting-payment
                $contract->status = 'awaiting-payment';
                $contract->save();

                return redirect()->route('congratulations', ['slug' => $data['contract']]);
            } else {
                return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'IPFS uploading is not working at the moment, please try to sign this contract later again.']);
            }
        } else {
            return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'IPFS uploading is not working at the moment, please try to sign this contract later again.']);
        }
    }

    protected function storePdfFileTemporally($html_start, $html_body, $html_end, $pdf_file_path) {
        $dompdf = new DOMPDF();
        $dompdf->load_html($html_start . '<div style="word-wrap: break-word;">'. $html_body . '</div>' . $html_end);
        $dompdf->render();
        $pdf_file = $dompdf->output();

        //saving the pdf file to the contracts folder, but this will be temporally
        file_put_contents($pdf_file_path, $pdf_file);
    }

    protected function getIndacoinPricesInUSD($currency)    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://indacoin.com/api/GetCoinConvertAmount/USD/'.$currency.'/100/dentacoin',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        // / 100 because we need the dcns for 1USD, we cannot make API request for 1USD
        return $resp / 100;
    }

    protected function submitReconsiderMonthlyPremium(Request $request) {
        $data = $this->clearPostData($request->input());
        $this->validate($request, [
            'dentist-email' => 'required',
            'dentist-name' => 'required',
            'contract' => 'required',
            'new-usd-proposal-to-dentist' => 'required'
        ], [
            'dentist-email.required' => 'Dentist email is required.',
            'dentist-name.required' => 'Dentist name is required.',
            'contract.required' => 'Contract is required.',
            'new-usd-proposal-to-dentist.required' => 'Monthly premium proposal is required.',
        ]);

        $sender = (new APIRequestsController())->getUserData(session('logged_user')['id']);

        $body = '<!DOCTYPE html><html><head></head><body><div style="font-size: 16px;">Dear Dr. '.$data['dentist-name'].',<br><br><br>My name is <b>'.$sender->name.'</b> and I have successfully received my Assurance Contract Sample, but I’d like to suggest a monthly premium of '.$data['new-usd-proposal-to-dentist'].' USD in Dentacoin (DCN).<br><br>I hope you will reconsider your proposal.<br><br>Regards,<br><b>'.$sender->name.'</div></body></html>';

        Mail::send(array(), array(), function($message) use ($body, $data, $sender) {
            $message->to($data['dentist-email'])->subject('Reconsider Monthly Premium Proposal');
            $message->from($sender->email, $sender->name)->replyTo($sender->email, $sender->name);
            $message->setBody($body, 'text/html');
        });

        if(count(Mail::failures()) > 0) {
            return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'Email has not been sent to your dentist, please try again later.']);
        } else {
            return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['success' => 'Your monthly premium proposal has been sent successfully to your dentist.']);
        }
    }/*

    protected function PatientController(Request $request) {
        $this->validate($request, [
            'ipfs_hash' => 'required'
        ], [
            'ipfs_hash.required' => 'IPFS hash is required.'
        ]);

        $contract = TemporallyContract::where(array('document_hash' => $request->input('ipfs_hash')))->get()->first();
        $contract->status = 'awaiting-approval';
        $contract->save();

        return response()->json(['success' => true]);
    }*/

    protected function getContactClinicPopup(Request $request) {
        $this->validate($request, [
            'clinic_id' => 'required'
        ], [
            'clinic_id.required' => 'Clinic is required.'
        ]);

        $clinic = (new APIRequestsController())->getUserData($request->input('clinic_id'));
        $current_patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']);
        $receiver = $clinic->name . ' (' . $clinic->email . ')';
        if($clinic->is_clinic) {
            $title = '';
        } else {
            $title = 'Dr. ';
        }

        $body = '<div class="padding-bottom-25 padding-top-15">Dear '.$title.'<span class="calibri-bold">'.$clinic->name.'</span>, <br> I\'d like to receive a Dentacoin Assurance contract sample by you.<br><br><a href="'.BASE_URL.'dentist/create-contract" style="font-size: 20px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">CREATE CONTRACT SAMPLE</a><br><br>';

        $view = view('partials/before-sending-email-confirmation-popup', ['sender' => $current_patient, 'receiver' => $receiver, 'mail_title' => 'Send email to your dentist', 'mail_subject' => 'Please send me a Dentacoin Assurance contract sample', 'mail_body' => $body]);
        $view = $view->render();
        return response()->json(['success' => $view]);
    }

    protected function submitContactClinic(Request $request) {
        $this->validate($request, [
            'clinic_id' => 'required'
        ], [
            'clinic_id.required' => 'Clinic is required.'
        ]);

        $data = $this->clearPostData($request->input());
        $clinic = (new APIRequestsController())->getUserData($request->input('clinic_id'));
        var_dump($clinic);
        die();
        $sender = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        if($clinic->is_clinic) {
            $title = '';
        } else {
            $title = 'Dr. ';
        }

        $body = '<!DOCTYPE html><html><head></head><body><div style="font-size: 16px;">Dear '.$title.$clinic->name.', <br><br><br>I\'d like to receive a Dentacoin Assurance contract sample by you.<br><br><br><a href="'.BASE_URL.'dentist/create-contract" style="font-size: 20px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">CREATE CONTRACT SAMPLE</a><br><br><br><i style="font-size: 13px;">* Blockchain is just a new technology used for secure storage and exchange of value and data.</i></div></body></html>';

        Mail::send(array(), array(), function($message) use ($body, $clinic, $sender) {
            //$message->to($clinic->email)->subject('Please send me a Dentacoin Assurance contract sample');
            $message->to('testyserver@abv.bg')->subject('Please send me a Dentacoin Assurance contract sample');
            $message->from($sender->email, $sender->name)->replyTo($sender->email, $sender->name);
            $message->setBody($body, 'text/html');
        });

        if(count(Mail::failures()) > 0) {
            return redirect()->route($data['redirect'])->with(['error' => 'Email has not been sent to your dentist, please try again later.']);
        } else {
            return redirect()->route($data['redirect'])->with(['success' => 'Contract sample request has been sent to your dentist successfully.']);
        }
    }
}
