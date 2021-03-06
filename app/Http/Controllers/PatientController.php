<?php

namespace App\Http\Controllers;

use App\CalculatorParameter;
use App\ContractCheckup;
use App\ContractRecord;
use App\contractTransactionHash;
use App\FreeETHReceiver;
use App\InviteDentistsReward;
use App\PublicKey;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Dompdf\Dompdf;
use Endroid\QrCode\QrCode;

class PatientController extends Controller {

    public function getPatientAccess()    {
        if ((new UserController())->checkPatientSession()) {
            $logged_patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);
            $clinics = (new APIRequestsController())->getAllClinicsByName();
            $contracts = TemporallyContract::where(array('patient_id' => session('logged_user')['id']))->orWhere(array('patient_email' => $logged_patient->email))->get()->sortByDesc('created_at')->all();

            if (!empty($contracts)) {
                //IF PATIENT HAVE EXISTING CONTRACTS
                return view('pages/logged-user/patient/have-contracts', ['contracts' => $contracts, 'clinics' => $clinics]);
            } else {
                //IF PATIENT HAVE NO EXISTING CONTRACTS
                return view('pages/logged-user/patient/start-first-contract', ['clinics' => $clinics]);
            }
        } else {
            return (new HomeController())->getView();
        }
    }

    public function getNotLoggedView()   {
        return view('pages/patient', ['clinics' => (new APIRequestsController())->getAllClinicsByName()]);
    }

    protected function getInviteDentistsView() {
        return view('pages/logged-user/patient/invite-dentists', ['invited_dentists_list' => InviteDentistsReward::where(array('patient_id' => session('logged_user')['id']))->get()->sortByDesc('created_at')->all()]);
    }

    /*protected function getCongratulationsView($slug) {
        $contract = TemporallyContract::where(array('slug' => $slug, 'status' => 'awaiting-payment'))->get()->first();
        if (!empty($contract)) {
            return view('pages/logged-user/patient/congratulations', ['contract' => $contract, 'dcn_for_one_usd' => $this->getIndacoinPricesInUSD('DCN'), 'eth_for_one_usd' => $this->getIndacoinPricesInUSD('ETH')]);
        } else {
            return abort(404);
        }
    }*/

    protected function getPatientContractView($slug) {
        $contract = TemporallyContract::where(array('slug' => $slug))->get()->first();
        if ($contract->status == 'pending') {
            return abort(404);
        } else {
            if ($contract->status == 'active' || $contract->status == 'awaiting-approval') {
                //checking here if the contract withdraw period and grace period passed and the patient still didnt full in his wallet address
                $contract = (new UserController())->automaticContractCancel($contract);
            } else if ($contract->status == 'awaiting-payment') {
                $contract = (new UserController())->automaticContractCancel($contract, false);
            }

            $params = array('contract' => $contract);
            $contract_records = ContractRecord::where(array('contract_id' => $contract->id))->get()->all();
            $contract_checkups = ContractCheckup::where(array('contract_id' => $contract->id))->get()->all();

            if (!empty($contract_records) || !empty($contract_checkups)) {
                // merging records and checkups into recordshistory
                $recordsHistory = array_merge($contract_records, $contract_checkups);

                // ordering the merged result
                usort($recordsHistory, function($a, $b) {
                    return $a['created_at'] < $b['created_at'];
                });

                $params['recordsHistory'] = $recordsHistory;
            }

            $params['calculator_proposals'] = CalculatorParameter::where(array('code' => (new APIRequestsController())->getAllCountries()[(new APIRequestsController())->getUserData($contract->dentist_id)->country_id - 1]->code))->get(['param_gd_cd_id', 'param_gd_cd', 'param_gd_id', 'param_cd_id', 'param_gd', 'param_cd', 'param_id'])->first()->toArray();
            return view('pages/logged-user/patient/single-contract-view-'.$contract->status, $params);
        }
    }

    protected function getReconsiderMonthlyPremium(Request $request) {
        $contract = TemporallyContract::where(array('slug' => $request->input('contract'), 'status' => 'pending'))->get()->first();
        if ($contract && (new APIRequestsController())->getUserData(session('logged_user')['id'])->email == $contract->patient_email) {
            $view = view('emails/popup-reconsider-monthly-premium', ['dentist' => (new APIRequestsController())->getUserData($contract->dentist_id), 'contract' => $contract]);
            $view = $view->render();
            return response()->json(['success' => $view]);
        } else {
            return response()->json(['error' => 'Wrong data passed.']);
        }
    }

    protected function getInviteDentistsPopup(Request $request) {
        $data = $request->input('serialized');
        parse_str($data, $postdata);

        $current_patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']);
        $receiver = $postdata['title'] . ' ' . $postdata['dentist-name'] . ' (' . $postdata['email'] . ')';
        $body = '<div class="padding-bottom-25 padding-top-15">My name is <span class="calibri-bold">'.$current_patient->name.'</span> and I as a patient of yours I would like to invite you to join <span class="calibri-bold">Dentacoin Assurance</span> - the first blockchain* dental assurance that entitles patients to preventive dental care against affordable monthly premiums in Dentacoin (DCN) currency.</div><div class="padding-bottom-25">It’s very easy to start: Just sign up, wait for approval and create your first contract. <a href="'.BASE_URL.'" target="_blank" class="blue-green-color calibri-bold">See how it works.</a> After/ if I agree to the conditions offered, we will get into a trustful agreement benefiting from an automated payment & notification system.</div><div class="padding-bottom-20">Affordable, preventive care for me - regular income and loyal patients for you!</div><div class="padding-bottom-20"><a href="'.BASE_URL.'support-guide" target="_blank" class="blue-green-white-btn">LEARN MORE</a></div><div class="padding-bottom-30">Looking forward to seeing you onboard! If you need any further information, do not hesitate to contact the Dentacoin Assurance team at <a href="mailto:assurance@dentacoin.com" class="blue-green-color calibri-bold">assurance@dentacoin.com</a>.</div>';

        $view = view('emails/popup-module-sending-email-confirmation', ['sender' => $current_patient, 'receiver' => $receiver, 'mail_title' => 'Invite Your Dentist', 'mail_subject' => 'Invitation to join Dentacoin Assurance', 'mail_body' => $body]);
        $view = $view->render();
        return response()->json(['success' => $view]);
    }

    protected function inviteDentists(Request $request) {
        $this->validate($request, [
            'dentist-name' => 'required',
            'website' => 'required',
            'email' => 'required',
            'redirect' => 'required',
        ], [
            'dentist-name.required' => 'Dentist name is required.',
            'website.required' => 'Website is required.',
            'email.required' => 'Email is required.',
            'redirect.required' => 'Redirect is required.',
        ]);

        $data = $this->clearPostData($request->input());

        //check email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->route($data['redirect'])->with(['error' => 'Your form was not sent. Please try again with valid email.']);
        }

        //check if the invited dentist is already registered in the CoreDB
        $api_response = (new APIRequestsController())->checkIfFreeEmail($data['email']);
        if (!$api_response->success) {
            return redirect()->route($data['redirect'])->with(['error' => 'This dentist/ clinic is already register on Dentacoin.']);
        }

        //check if someone inviting already invited dentist
        if (InviteDentistsReward::where(array('dentist_email' => $data['email']))->get()->first()) {
            return redirect()->route($data['redirect'])->with(['error' => 'This dentist/ clinic is already invited in Assurance platform.']);
        }

        //===================================================================================
        //CHECK HERE IF THIS DENTIST EMAIL IS NOT ALREADY REGISTERED IN LOCAL DB AND IN API
        //===================================================================================

        $sender = (new APIRequestsController())->getUserData(session('logged_user')['id']);

        $email_view = view('emails/patient-inviting-dentist-to-assurance', ['dentist_name' => $data['title'].' '.$data['dentist-name'], 'sender' => $sender, 'base_url' => BASE_URL]);
        $body = $email_view->render();

        Mail::send(array(), array(), function($message) use ($body, $data, $sender) {
            $message->to($data['email'])->subject('Your patient '.$sender->name.' invited you to join Dentacoin Assurance');
            $message->from($sender->email, $sender->name)->replyTo($sender->email, $sender->name);
            $message->setBody($body, 'text/html');
        });

        if (count(Mail::failures()) > 0) {
            return redirect()->route($data['redirect'])->with(['error' => 'Email has not been sent to your dentist, please try again later.']);
        } else {
            $invite_dentist_reward = new InviteDentistsReward();
            $invite_dentist_reward->patient_id = session('logged_user')['id'];
            $invite_dentist_reward->dentist_email = $data['email'];
            $invite_dentist_reward->title = $data['title'];
            $invite_dentist_reward->name = $data['dentist-name'];
            $invite_dentist_reward->website = $data['website'];
            if (!empty($data['phone'])) {
                $invite_dentist_reward->phone = $data['phone'];
            }

            //saving to DB
            $invite_dentist_reward->save();
            return redirect()->route($data['redirect'])->with(['success' => true, 'popup-html' => '<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30 padding-left-50 padding-right-50 padding-left-xs-0 padding-right-xs-0">YOUR INVITATION HAS BEEN SENT SUCCESSFULLY.</div><div class="padding-top-20 padding-bottom-15 fs-20 padding-left-50 padding-right-50 padding-left-xs-0 padding-right-xs-0">You have invited <span class="calibri-bold blue-green-color">'.$data['title'].' '.$data['dentist-name'].'</span> to join Dentacoin Assurance. Once they register, you will receive your reward of <span class="calibri-bold blue-green-color">20,000 DCN</span>!</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0)" class="white-blue-green-btn min-width-200 close-popup">OK</a></div></div>']);
        }
    }

    protected function getContractProposal($slug) {
        $contract = TemporallyContract::where(array('slug' => $slug, 'status' => 'pending'))->get()->first();
        if ((new UserController())->checkDentistSession() || empty($contract)) {
            //if dentist trying to access the proposal or if there is no such contract
            return abort(404);
        } else if ((new UserController())->checkPatientSession()) {
            // if pending and the contract proposal expired redirect to homepage
            if ((time() - strtotime($contract->created_at->format('d-m-Y'))) / (60 * 60 * 24) > DAYS_ACTIVE_CONTRACT_PROPOSAL) {
                return redirect()->route('patient-access')->with(['error' => 'This contract proposal has expired.']);
            }

            $current_logged_patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);

            $params = array(
                'contract' => $contract,
                'current_logged_patient' => $current_logged_patient,
                'countries' => (new APIRequestsController())->getAllCountries(),
                'shown' => 'one'
            );

            $existing_id_number = TemporallyContract::where(array('patient_id' => $current_logged_patient->id))->where('patient_id_number', '!=' , '')->get()->first();

            $patient_id_number = '';
            if ($existing_id_number && $existing_id_number->patient_id_number != '') {
                $patient_id_number = $existing_id_number->patient_id_number;
            }

            $params['patient_id_number'] = $patient_id_number;
            $params['patient'] = $current_logged_patient;

            if (!empty($contract->patient_id_number)) {
                $params['shown'] = 'two';
            }
            //if patient is logged in and if the contract is about the logged patient
            return view('pages/logged-user/patient/review-assurance-contract', $params);
        } else {
            return view('pages/contract-proposal-partly', ['contract' => $contract, 'countries' => (new APIRequestsController())->getAllCountries()]);
        }
    }

    protected function updateAndSignContract(Request $request) {
        $logged_patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        $edited_patient_account = false;

        $required_fields_arr = array(
            'patient_signature' => 'required',
            'patient-id-number' => 'required|max:20',
            'contract' => 'required',
            'dcn_address' => 'required|min:42',
        );
        $required_fields_msgs_arr = array(
            'patient_signature.required' => 'Patient signature is required.',
            'patient-id-number.required' => 'Patient ID number signature is required.',
            'contract.required' => 'Contract slug is required.',
            'dcn_address.required' => 'Wallet Address is required.',
            'dcn_address.min' => 'Wallet Address is invalid.',
        );

        if (empty($logged_patient->country_id)) {
            $required_fields_arr['country'] = 'required';
            $required_fields_msgs_arr['country.required'] = 'Country is required.';
        }

        if (empty($logged_patient->address)) {
            $required_fields_arr['address'] = 'required';
            $required_fields_msgs_arr['address.required'] = 'Postal Address is required.';
        }

        $this->validate($request, $required_fields_arr, $required_fields_msgs_arr);

        $data = $this->clearPostData($request->input());
        $contract = TemporallyContract::where(array('slug' => $data['contract']))->get()->first();
        $dentist = (new APIRequestsController())->getUserData($contract->dentist_id);

        //if empty contract
        if (empty($contract) /*|| (!empty($contract) && $contract->patient_email != $logged_patient->email)*/) {
            return abort(404);
        }

        //check if contract expired
        if ((time() - $contract->created_at->timestamp) / (60 * 60 * 24) > DAYS_ACTIVE_CONTRACT_PROPOSAL) {
            return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'This contract proposal has expired.']);
        }

        //check if have other contract with status active, pending, awaiting-payment or awaiting-approval
        $other_contracts = TemporallyContract::where(array('dentist_id' => $contract->dentist_id, 'patient_id' => session('logged_user')['id']))->whereIn('status', array('active', 'awaiting-payment', 'awaiting-approval', 'pending'))->get()->all();
        foreach($other_contracts as $other_contract) {
            if ($other_contract->id != $contract->id) {
                return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'You cannot have more than one pending or active contract with same dentist. Please first cancel your previous contract with '.$dentist->name.' and then try to sign this contract.']);
            }
        }

        //update CoreDB api data for this patient
        if (isset($data['country']) || isset($data['address'])) {
            $edited_patient_account = true;
            $curl_arr = array();
            if (isset($data['country'])) {
                if (!empty($data['country'])) {
                    $curl_arr['country_code'] = $data['country'];
                }else {
                    return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'Country is required']);
                }
            }
            if (isset($data['address'])) {
                if (!empty($data['address'])) {
                    $curl_arr['address'] = $data['address'];
                }else {
                    return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'Postal Address is required']);
                }
            }

            //handle the API response
            $api_response = (new APIRequestsController())->updateUserData($curl_arr);
            if (!$api_response) {
                return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['errors_response' => $api_response['errors']]);
            }
        }

        if ($edited_patient_account) {
            //asking for logged_patient updated data
            $logged_patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        }

        //getting the public key for this address stored in the assurance db (this table is getting updated by wallet.dentacoin.com)
        $patient_pub_key = PublicKey::where(array('address' => $data['dcn_address']))->get()->first();
        $dentist_pub_key = PublicKey::where(array('address' => $contract->dentist_address))->get()->first();

        if (empty($patient_pub_key) || empty($dentist_pub_key)) {
            return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => 'No such public keys in the database.']);
        }

        //querying again the patient data from the CoreDB because we did changes to it in this method earlier
        $logged_patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);

        //create image from the base64 signature
        $signature_filename = 'patient-signature.png';
        $temp_contract_folder_path = CONTRACTS . $data['contract'];
        file_put_contents($temp_contract_folder_path . DS . $signature_filename, $this->base64ToPng($data['patient_signature']));

        $contract->patient_id = $logged_patient->id;
        $contract->patient_full_name = $logged_patient->name;
        $contract->patient_email = $logged_patient->email;
        $contract->patient_id_number = $data['patient-id-number'];
        $contract->patient_address = $data['dcn_address'];
        $contract->patient_street_address = $logged_patient->address;
        $countries = (new APIRequestsController())->getAllCountries();
        $contract->patient_country = $countries[$logged_patient->country_id - 1]->name;
        $contract->contract_active_at = new \DateTime();

        //GENERATE PDF
        $view_start = view('partials/pdf-contract-layout-start');
        $html_start = $view_start->render();

        $view_body = view('partials/pdf-contract-body', ['contract' => $contract, 'countries' => (new APIRequestsController())->getAllCountries()]);
        $html_body = $view_body->render();

        $view_end = view('partials/pdf-contract-layout-end');
        $html_end = $view_end->render();

        //sending the pdf html to encryption nodejs api
        $encrypted_html_by_patient = (new \App\Http\Controllers\APIRequestsController())->encryptFile($patient_pub_key->public_key, htmlentities($html_body));
        $encrypted_html_by_dentist = (new \App\Http\Controllers\APIRequestsController())->encryptFile($dentist_pub_key->public_key, htmlentities($html_body));

        //if no errors from the api
        if ($encrypted_html_by_patient && !isset($encrypted_html_by_patient->error) && $encrypted_html_by_dentist && !isset($encrypted_html_by_dentist->error)) {
            $this->storePdfFileTemporally($html_start, $encrypted_html_by_patient->success->encrypted, $html_end, CONTRACTS . $contract->slug . DS . 'patient-pdf-file.pdf');
            $this->storePdfFileTemporally($html_start, $encrypted_html_by_dentist->success->encrypted, $html_end, CONTRACTS . $contract->slug . DS . 'dentist-pdf-file.pdf');

            //creating zip file with the both encrypted pdfs
            $contract_folder_relative_path = 'assets' . DS . 'contracts' . DS . $contract->slug . DS;
            $zipper = new \Chumper\Zipper\Zipper;
            $zip_name = 'assurance-contracts.zip';
            $zipper->make($contract_folder_relative_path . $zip_name)->add([$contract_folder_relative_path . 'dentist-pdf-file.pdf', $contract_folder_relative_path . 'patient-pdf-file.pdf']);
            $zipper->close();

            $ipfs_hash = (new \App\Http\Controllers\APIRequestsController())->uploadFileToIPFS(BASE_URL . 'assets' . DS . 'contracts' . DS . $contract->slug . DS . $zip_name);
            if ($ipfs_hash->response_obj && $ipfs_hash->response_obj->success) {
                $contract->document_hash = $ipfs_hash->response_obj->success->hash;

                //deleting the contract folder
                /*array_map('unlink', glob(CONTRACTS . $contract->slug . '/*.*'));
                rmdir(CONTRACTS . $contract->slug);*/

                //updating the status to awaiting-payment
                $contract->status = 'awaiting-payment';

                $this_patient_having_contracts = TemporallyContract::where(array('patient_id' => $logged_patient->id))->get()->all();
                $alreadySentEthToThisUser = FreeETHReceiver::where(array('walletAddress' => $contract->patient_address))->get()->first();

                //send ETH amount to patient
                if (sizeof($this_patient_having_contracts) == 0 && empty($alreadySentEthToThisUser)) {
                    //only if no previous contracts, aka sending only for first contract
                    //request to nodejs server, if the transaction fails the nodejs server will recover back the tokens to user balance in DB
                    $gasPrice = (int)(new APIRequestsController())->getGasEstimationFromEthgasstation();
                    $sendEthAmountParams = array(
                        'patient_address' => $contract->patient_address,
                        'dentist_address' => $contract->dentist_address,
                        'type' => 'patient-approval-and-contract-creation',
                        'gas_price' => $gasPrice
                    );

                    $sending_eth_response = (new \App\Http\Controllers\APIRequestsController())->sendEthAmount(hash(getenv('HASHING_METHOD'), getenv('SECRET_PASSWORD').json_encode($sendEthAmountParams)), 'patient-approval-and-contract-creation', $contract->patient_address, $contract->dentist_address, $gasPrice, $contract->monthly_premium, $contract->monthly_premium * (int)$this->getIndacoinPricesInUSD('DCN'), $contract->contract_active_at->getTimestamp(), $contract->document_hash);

                    // saving record that we sent eth amount to this user
                    $freeETHReceiver = new FreeETHReceiver();
                    $freeETHReceiver->walletAddress = $contract->patient_address;
                    $freeETHReceiver->save();

                    if (is_object($sending_eth_response) && property_exists($sending_eth_response, 'success') && $sending_eth_response->success) {
                        $freeETHReceiver->txHash = $sending_eth_response->transactionHash;
                        $freeETHReceiver->save();

                        $contractRecord = new ContractRecord();
                        $contractRecord->contract_id = $contract->id;
                        $contractRecord->type = 'Contract signed by patient';
                        $contractRecord->save();

                        $email_view = view('emails/patient-sign-contract', ['dentist' => $dentist, 'patient' => $logged_patient, 'contract' => $contract]);
                        $body = $email_view->render();

                        Mail::send(array(), array(), function($message) use ($body, $dentist, $logged_patient) {
                            $message->to($dentist->email)->subject('Your patient '.$logged_patient->name.' has signed their contract');
                            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                            $message->setBody($body, 'text/html');
                        });

                        $contract->save();
                        return redirect()->route('patient-contract-view', ['slug' => $data['contract']])/*->with(['congratulations' => true])*/;
                    } else {
                        // deleting the record if the transaction fails
                        $freeETHReceiver->delete();

                        return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => "1 IPFS uploading is not working at the moment, please try to sign this contract later again or contact <a href='mailto:assurance@dentacoin.com'>Dentacoin team</a>."]);
                    }
                } else {
                    $contractRecord = new ContractRecord();
                    $contractRecord->contract_id = $contract->id;
                    $contractRecord->type = 'Contract signed by patient';
                    $contractRecord->save();

                    $email_view = view('emails/patient-sign-contract', ['dentist' => $dentist, 'patient' => $logged_patient, 'contract' => $contract]);
                    $body = $email_view->render();

                    Mail::send(array(), array(), function($message) use ($body, $dentist, $logged_patient) {
                        $message->to($dentist->email)->subject('Your patient '.$logged_patient->name.' has signed their contract');
                        $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                        $message->setBody($body, 'text/html');
                    });

                    $contract->save();

                    return redirect()->route('patient-contract-view', ['slug' => $data['contract']])/*->with(['congratulations' => true])*/;
                }
            } else {
                return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => "2 IPFS uploading is not working at the moment, please try to sign this contract later again or contact <a href='mailto:assurance@dentacoin.com'>Dentacoin team</a>."]);
            }
        } else {
            return redirect()->route('contract-proposal', ['slug' => $data['contract']])->with(['error' => "3 IPFS uploading is not working at the moment, please try to sign this contract later again or contact <a href='mailto:assurance@dentacoin.com'>Dentacoin team</a>."]);
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

    public function getIndacoinPricesInUSD($currency)    {
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

        if (!empty($resp)) {
            return $resp / 100;
        } else {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => "https://api.coingecko.com/api/v3/coins/dentacoin",
                CURLOPT_SSL_VERIFYPEER => 0
            ));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $resp = json_decode(curl_exec($curl));
            curl_close($curl);

            if (!empty($resp))   {
                if (property_exists($resp, 'market_data') && !empty($resp->market_data->current_price) )  {
                    return 1 / $resp->market_data->current_price->usd;
                }else {
                    return 0;
                }
            }
        }
    }

    protected function submitReconsiderMonthlyPremium(Request $request) {
        $this->validate($request, [
            'serialized' => 'required'
        ], [
            'serialized.required' => 'Data is required.'
        ]);
        parse_str($request->input('serialized'), $data);

        $sender = (new APIRequestsController())->getUserData(session('logged_user')['id']);

        $contract = TemporallyContract::where(array('slug' => $data['contract'], 'patient_email' => $sender->email))->get()->first();
        if (!empty($contract)) {
            $email_view = view('emails/reconsider-monthly-premium', ['dentist_name' => $data['dentist-name'], 'usd_proposal' => $data['new-usd-proposal-to-dentist'], 'sender_name' => $sender->name, 'contract' => $contract]);
            $body = $email_view->render();

            Mail::send(array(), array(), function($message) use ($body, $data, $sender) {
                $message->to($data['dentist-email'])->subject('Reconsider Monthly Premium Proposal');
                $message->from($sender->email, $sender->name)->replyTo($sender->email, $sender->name);
                $message->setBody($body, 'text/html');
            });

            if (count(Mail::failures()) > 0) {
                return response()->json(['error' => 'Email has not been sent to your dentist, please try again later.']);
            } else {
                return response()->json(['success' => '<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">EMAIL SUCCESSFULLY SENT</div><div class="padding-top-20 padding-bottom-15 fs-20">Your monthly premium proposal has been successfully sent to your dentist.</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0)" class="white-blue-green-btn min-width-200 close-popup">OK</a></div></div>']);
            }
        } else {
            return response()->json(['error' => 'False contract.']);
        }
    }

    public function changeToAwaitingApprovalStatus($contract) {
        $contract->status = 'awaiting-approval';
        $contract->is_processing = false;
        $contract->save();

        // let cronjob check know that database is synced with this transaction status
        $contractTransactionHash = contractTransactionHash::where(array('contract_slug' => $contract->slug, 'to_status' => 'awaiting-approval'))->get()->first();
        if (!empty($contractTransactionHash)) {
            $contractTransactionHash->synced_with_assurance_db = true;
            $contractTransactionHash->save();
        }

        $dentist = (new APIRequestsController())->getUserData($contract->dentist_id);
        $logged_patient = (new APIRequestsController())->getUserData($contract->patient_id);

        $email_view = view('emails/patient-sign-contract', ['dentist' => $dentist, 'patient' => $logged_patient, 'contract' => $contract]);
        $body = $email_view->render();

        Mail::send(array(), array(), function($message) use ($body, $dentist, $logged_patient) {
            $message->to($dentist->email)->subject('Approve your contract with '.$logged_patient->name);
            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
            $message->setBody($body, 'text/html');
        });

        $contractRecord = new ContractRecord();
        $contractRecord->contract_id = $contract->id;
        $contractRecord->type = 'Contract activated by patient';
        $contractRecord->save();

        // send ETH to dentist for the approval
        /*$gasPrice = (int)(new APIRequestsController())->getGasEstimationFromEthgasstation();
        $sendEthAmountParams = array(
            'patient_address' => $contract->patient_address,
            'dentist_address' => $contract->dentist_address,
            'type' => 'dentist-approval',
            'gas_price' => $gasPrice
        );

        // saving record that we sent eth amount to this user
        $freeETHReceiver = new FreeETHReceiver();
        $freeETHReceiver->walletAddress = $contract->dentist_address;
        $freeETHReceiver->save();

        $sending_eth_response = (new \App\Http\Controllers\APIRequestsController())->sendEthAmount(hash(getenv('HASHING_METHOD'), getenv('SECRET_PASSWORD').json_encode($sendEthAmountParams)), 'dentist-approval', $contract->patient_address, $contract->dentist_address, $gasPrice);
        if(is_object($sending_eth_response) && property_exists($sending_eth_response, 'success') && $sending_eth_response->success) {
            $freeETHReceiver->txHash = $sending_eth_response->transactionHash;
            $freeETHReceiver->save();
        } else {
            // deleting the record if the transaction fails
            $freeETHReceiver->delete();
        }*/
    }

    protected function getContactClinicPopup(Request $request) {
        $this->validate($request, [
            'clinic_id' => 'required'
        ], [
            'clinic_id.required' => 'Clinic is required.'
        ]);

        $clinic = (new APIRequestsController())->getUserData($request->input('clinic_id'));
        $current_patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']);
        $receiver = $this->prepareUserName($clinic) . ' (' . $clinic->email . ')';

        $body = '<div class="padding-bottom-25 padding-bottom-xs-0 padding-top-15">Dear <span class="calibri-bold">'.$this->prepareUserName($clinic).'</span>, <br> I\'d like to receive a Dentacoin Assurance contract sample by you.<br><br><a href="'.BASE_URL.'dentist/create-contract" class="blue-green-white-btn fs-xs-16" target="_blank">CREATE CONTRACT SAMPLE</a><br><br>';

        $view = view('emails/popup-module-sending-email-confirmation', ['sender' => $current_patient, 'receiver' => $receiver, 'mail_title' => 'Send email to your dentist', 'mail_subject' => 'Please send me a Dentacoin Assurance contract sample', 'mail_body' => $body]);
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
        $sender = (new APIRequestsController())->getUserData(session('logged_user')['id']);

        $email_view = view('emails/patient-requesting-assurance-contract-from-registered-dentist', ['dentist_name' => $this->prepareUserName($clinic), 'base_url' => BASE_URL, 'sender' => $sender]);
        $body = $email_view->render();

        Mail::send(array(), array(), function($message) use ($body, $clinic, $sender) {
            $message->to($clinic->email)->subject('Please send me a Dentacoin Assurance contract sample');
            $message->from($sender->email, $sender->name)->replyTo($sender->email, $sender->name);
            $message->setBody($body, 'text/html');
        });

        if (count(Mail::failures()) > 0) {
            return redirect()->route($data['redirect'])->with(['error' => 'Email has not been sent to your dentist, please try again later.']);
        } else {
            return redirect()->route($data['redirect'])->with(['success' => 'Contract sample request has been sent to your dentist successfully.']);
        }
    }

    protected function recordCheckUpOrTeethCleaning(Request $request) {
        $this->validate($request, [
            'type' => 'required',
            'contract' => 'required'
        ], [
            'type.required' => 'Type is required.',
            'contract.required' => 'Contract is required.'
        ]);

        $date = $request->input('date');
        $check_up_date = $request->input('check_up_date');
        $teeth_cleaning_date = $request->input('teeth_cleaning_date');

        if (empty($date) && empty($check_up_date) && empty($teeth_cleaning_date)) {
            return response()->json(['error' => true, 'message' => 'Missing dates.']);
        }

        $contract = TemporallyContract::where(array('slug' => $request->input('contract'), 'patient_id' => session('logged_user')['id'], 'status' => 'active'))->get()->first();
        if (!empty($contract)) {
            $timeSinceContractSigning = (new \App\Http\Controllers\Controller())->convertMS(time() - strtotime($contract->contract_active_at));
            $yearsActionsToBeExecuted = 1;
            if (array_key_exists('days', $timeSinceContractSigning) && $timeSinceContractSigning['days'] >= 365) {
                $yearsActionsToBeExecuted += floor($timeSinceContractSigning['days'] / 365);
            }

            $periodBegin = date('Y-m-d', strtotime(' + ' . (365 * ($yearsActionsToBeExecuted - 1)) . ' days', strtotime($contract->contract_active_at)));
            $periodEnd = date('Y-m-d', strtotime(' + ' . (365 * $yearsActionsToBeExecuted) . ' days', strtotime($contract->contract_active_at)));

            $dentist = (new APIRequestsController())->getUserData($contract->dentist_id);
            $dentistEmail = $dentist->email;
            $patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);
            if ($request->input('type') == 'check-up' || $request->input('type') == 'teeth-cleaning') {
                if (empty($date) && \DateTime::createFromFormat('Y-m-d', $date) !== FALSE) {
                    return response()->json(['error' => true, 'message' => 'Missing or invalid date.']);
                }

                if ($request->input('type') == 'check-up') {
                    $currentRecordsCount = $this->getCheckUpOrTeethCleaning('check-up', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'));
                    $event = $currentRecordsCount + 1;
                    $aMustRecordsCount = $contract->check_ups_per_year;

                    $email_view = view('emails/patient-recording-check-up', ['dentist' => $dentist, 'patient' => $patient, 'contract_slug' => $contract->slug, 'visit_date' => date('Y-m-d', strtotime($request->input('date')))]);
                    $email_body = $email_view->render();
                    $email_subject = 'ACTION REQUIRED: Confirm '.$patient->name.'\'s visit';
                    $type = 'check-ups';
                } else if ($request->input('type') == 'teeth-cleaning') {
                    $currentRecordsCount = $this->getCheckUpOrTeethCleaning('teeth-cleaning', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'));
                    $event = $currentRecordsCount + 1;
                    $aMustRecordsCount = $contract->teeth_cleaning_per_year;

                    $email_view = view('emails/patient-recording-teeth-cleaning', ['dentist' => $dentist, 'patient' => $patient, 'contract_slug' => $contract->slug, 'visit_date' => date('Y-m-d', strtotime($request->input('date')))]);
                    $email_body = $email_view->render();
                    $email_subject = 'ACTION REQUIRED: Confirm '.$patient->name.'\'s visit';
                    $type = 'teeth cleaning';
                }

                if ($currentRecordsCount < $aMustRecordsCount) {
                    $checkUp = new ContractCheckup();
                    $checkUp->contract_id = $contract->id;
                    $checkUp->type = $request->input('type');
                    $checkUp->event = $event;
                    $checkUp->request_date_at = date('Y-m-d H:i:s', strtotime($date));

                    Mail::send(array(), array(), function($message) use ($email_body, $email_subject, $dentistEmail) {
                        $message->to($dentistEmail)->subject($email_subject);
                        $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                        $message->setBody($email_body, 'text/html');
                    });

                    if (count(Mail::failures()) > 0) {
                        return response()->json(['error' => true, 'message' => 'Record saving and email sending to dentist failed, please try again later or contact <a href="mailto:'.EMAIL_SENDER.'">'.EMAIL_SENDER.'</a>.']);
                    } else {
                        $checkUp->save();

                        return response()->json(array('success' => true, 'id' => $checkUp->id, 'event' => $event, 'mustRecordsCount' => $aMustRecordsCount));
                    }
                } else {
                    return response()->json(['error' => true, 'message' => 'You already have ' . $aMustRecordsCount . ' ' . $type . ' recorded, as agreed in the contract terms.']);
                }
            } else if ($request->input('type') == 'check-up-and-teeth-cleaning') {
                if (empty($check_up_date) && empty($teeth_cleaning_date)) {
                    return response()->json(['error' => true, 'message' => 'Missing dates.']);
                }

                if (!empty($check_up_date) || !empty($teeth_cleaning_date)) {
                    $currentCheckUpRecordsCount = $this->getCheckUpOrTeethCleaning('check-up', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'));
                    $checkUpEvent = $currentCheckUpRecordsCount + 1;
                    $currentTeethCleaningRecordsCount = $this->getCheckUpOrTeethCleaning('teeth-cleaning', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'));
                    $teethCleaningUpEvent = $currentTeethCleaningRecordsCount + 1;
                    $aMustCheckUpRecordsCount = $contract->teeth_cleaning_per_year;
                    $aMustTeethCleaningRecordsCount = $contract->teeth_cleaning_per_year;

                    if ($currentCheckUpRecordsCount < $aMustCheckUpRecordsCount && !empty($check_up_date)) {
                        $checkUp = new ContractCheckup();
                        $checkUp->contract_id = $contract->id;
                        $checkUp->type = 'check-up';
                        $checkUp->event = $checkUpEvent;
                        $checkUp->request_date_at = date('Y-m-d H:i:s', strtotime($check_up_date));
                    }

                    if ($currentTeethCleaningRecordsCount < $aMustTeethCleaningRecordsCount && !empty($teeth_cleaning_date)) {
                        $teethCleaning = new ContractCheckup();
                        $teethCleaning->contract_id = $contract->id;
                        $teethCleaning->type = 'teeth-cleaning';
                        $teethCleaning->event = $teethCleaningUpEvent;
                        $teethCleaning->request_date_at = date('Y-m-d H:i:s', strtotime($teeth_cleaning_date));
                    }

                    if (isset($checkUp) && isset($teethCleaning)) {
                        // CHECK UP AND TEETH CLEANING
                        $email_view = view('emails/patient-recording-check-up-and-teeth-cleaning', ['dentist' => $dentist, 'patient' => $patient, 'contract_slug' => $contract->slug, 'check_up_date' => date('Y-m-d', strtotime($check_up_date)), 'teeth_cleaning_date' => date('Y-m-d H:i:s', strtotime($teeth_cleaning_date))]);
                        $email_body = $email_view->render();
                        $email_subject = 'ACTION REQUIRED: Confirm '.$patient->name.'\'s visit';

                        Mail::send(array(), array(), function($message) use ($email_body, $email_subject, $dentistEmail) {
                            $message->to($dentistEmail)->subject($email_subject);
                            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                            $message->setBody($email_body, 'text/html');
                        });

                        if (count(Mail::failures()) > 0) {
                            return response()->json(['error' => true, 'message' => 'Record saving and email sending to dentist failed, please try again later or contact <a href="mailto:'.EMAIL_SENDER.'">'.EMAIL_SENDER.'</a>.']);
                        } else {
                            $checkUp->save();
                            $teethCleaning->save();

                            return response()->json(array('success' => true, 'checkUpId' => $checkUp->id, 'teethCleaningId' => $teethCleaning->id, 'checkUpEvent' => $checkUpEvent, 'teethCleaningUpEvent' => $teethCleaningUpEvent, 'aMustCheckUpRecordsCount' => $aMustCheckUpRecordsCount, 'aMustTeethCleaningRecordsCount' => $aMustTeethCleaningRecordsCount));
                        }
                    } else if (isset($checkUp)) {
                        // CHECK UP
                        $email_view = view('emails/patient-recording-check-up', ['dentist' => $dentist, 'patient' => $patient, 'contract_slug' => $contract->slug, 'visit_date' => date('Y-m-d H:i:s', strtotime($request->input('date')))]);
                        $email_body = $email_view->render();
                        $email_subject = 'ACTION REQUIRED: Confirm '.$patient->name.'\'s visit';

                        Mail::send(array(), array(), function($message) use ($email_body, $email_subject, $dentistEmail) {
                            $message->to($dentistEmail)->subject($email_subject);
                            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                            $message->setBody($email_body, 'text/html');
                        });

                        if (count(Mail::failures()) > 0) {
                            return response()->json(['error' => true, 'message' => 'Record saving and email sending to dentist failed, please try again later or contact <a href="mailto:'.EMAIL_SENDER.'">'.EMAIL_SENDER.'</a>.']);
                        } else {
                            $checkUp->save();

                            return response()->json(array('success' => true));
                        }
                    } else if (isset($teethCleaning)) {
                        // TEETH CLEANING
                        $email_view = view('emails/patient-recording-teeth-cleaning', ['dentist' => $dentist, 'patient' => $patient, 'contract_slug' => $contract->slug, 'visit_date' => date('Y-m-d H:i:s', strtotime($request->input('date')))]);
                        $email_body = $email_view->render();
                        $email_subject = 'ACTION REQUIRED: Confirm '.$patient->name.'\'s visit';

                        Mail::send(array(), array(), function($message) use ($email_body, $email_subject, $dentistEmail) {
                            $message->to($dentistEmail)->subject($email_subject);
                            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                            $message->setBody($email_body, 'text/html');
                        });

                        if (count(Mail::failures()) > 0) {
                            return response()->json(['error' => true, 'message' => 'Record saving and email sending to dentist failed, please try again later or contact <a href="mailto:'.EMAIL_SENDER.'">'.EMAIL_SENDER.'</a>.']);
                        } else {
                            $teethCleaning->save();

                            return response()->json(array('success' => true));
                        }
                    }
                } else {
                    return response()->json(['error' => true, 'message' => 'Missing dates.']);
                }
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Missing contract.']);
        }
    }

    // returning the count of approved by dentist checkups for contract for period of time (year)
    public function getCheckUpOrTeethCleaning($type, $slug, $from, $to, $statuses = array('approved'), $dentist = false) {
        if ($dentist) {
            $checkUps = ContractCheckup::leftJoin('temporally_contracts', function($join) {
                $join->on('contract_checkups.contract_id', '=', 'temporally_contracts.id');
            })->where(array('temporally_contracts.dentist_id' => session('logged_user')['id'], 'temporally_contracts.slug' => $slug, 'contract_checkups.type' => $type))->whereIn('contract_checkups.status', $statuses)->whereBetween('contract_checkups.request_date_at', array($from, $to))->get()->all();
        } else {
            $checkUps = ContractCheckup::leftJoin('temporally_contracts', function($join) {
                $join->on('contract_checkups.contract_id', '=', 'temporally_contracts.id');
            })->where(array('temporally_contracts.patient_id' => session('logged_user')['id'], 'temporally_contracts.slug' => $slug, 'contract_checkups.type' => $type))->whereIn('contract_checkups.status', $statuses)->whereBetween('contract_checkups.request_date_at', array($from, $to))->get()->all();
        }

        if (!empty($checkUps)) {
            return sizeof($checkUps);
        } else {
            return 0;
        }
    }

    public function checkContractsCount() {
        $logged_patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        $contracts = TemporallyContract::where(array('patient_email' => $logged_patient->email))->get()->all();

        if (!empty($contracts)) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function checkForIncomingPendingContracts(Request $request) {
        $this->validate($request, [
            'now_timestamp' => 'required'
        ], [
            'now_timestamp.required' => 'Time is required.'
        ]);

        $logged_patient = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        $contracts = TemporallyContract::where(array('patient_email' => $logged_patient->email, 'status' => 'pending'))->where('created_at', '>=', date('Y-m-d H:i:s', $request->input('now_timestamp')))->get()->all();

        if (!empty($contracts)) {
            $responseContracts = array();
            foreach($contracts as $contract) {
                $tempContract = array();
                $dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id);

                $tempContract['dentistAvatar'] = $dentist->avatar_url;
                $tempContract['dentistName'] = $this->prepareUserName($dentist);
                $tempContract['status'] = $contract->status;
                $tempContract['monthlyPremium'] = $contract->monthly_premium;
                $tempContract['createdAt'] = $contract->created_at->format('d/m/Y');
                $tempContract['slug'] = $contract->slug;
                $tempContract['status'] = 'Pending';
                $tempContract['url'] = route('contract-proposal', ['slug' => $contract->slug]);
                $tempContract['btnLabel'] = 'Details and Sign';

                array_push($responseContracts, $tempContract);
            }

            return response()->json(array('success' => true, 'data' => $responseContracts));
        } else {
            return response()->json(['error' => true]);
        }
    }
}
