<?php

namespace App\Http\Controllers;

use App\CalculatorParameter;
use App\ContractCheckup;
use App\ContractRecord;
use App\contractTransactionHash;
use App\FreeETHReceiver;
use App\InviteDentistsReward;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class DentistController extends Controller
{
    public function getNoContractsView() {
        return view('pages/logged-user/dentist/homepage-no-contracts');
    }

    public function getDentistContractView($slug) {
        $contract = TemporallyContract::where(array('slug' => $slug))->get()->first();
        if (!empty($contract)) {
            $current_logged_dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']);
            $calculator_proposals = CalculatorParameter::where(array('code' => (new APIRequestsController())->getAllCountries()[$current_logged_dentist->country_id - 1]->code))->get(['param_gd_cd_id', 'param_gd_cd', 'param_gd_id', 'param_cd_id', 'param_gd', 'param_cd', 'param_id'])->first()->toArray();
            $params = ['contract' => $contract, 'calculator_proposals' => $calculator_proposals, 'current_logged_dentist' => $current_logged_dentist];

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

            if ($contract->status == 'active' || $contract->status == 'awaiting-approval') {
                //checking here if the contract withdraw period and grace period passed and the patient still didnt full in his wallet address
                $contract = (new UserController())->automaticContractCancel($contract);
            } else if ($contract->status == 'awaiting-payment' || $contract->status == 'pending') {
                // if pending and the contract proposal expired redirect to homepage
                if ($contract->status == 'pending' && (time() - strtotime($contract->created_at->format('d-m-Y'))) / (60 * 60 * 24) > DAYS_ACTIVE_CONTRACT_PROPOSAL) {
                    return redirect()->route('home')->with(['error' => 'This contract proposal has expired.']);
                }

                $contract = (new UserController())->automaticContractCancel($contract, false);
            }

            if ($contract->status == 'awaiting-approval') {
                /*$this_dentist_having_contracts = TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->all();
                $alreadySentEthToThisUser = FreeETHReceiver::where(array('walletAddress' => $contract->dentist_address))->get()->first();

                if (sizeof($this_dentist_having_contracts) == 1 && empty($alreadySentEthToThisUser)) {
                    //send ETH to dentist only for his first contract
                    $gasPrice = (int)(new APIRequestsController())->getGasEstimationFromEthgasstation();
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

                        $params['sent_eth_to_dentist'] = true;
                    } else {
                        // deleting the record if the transaction fails
                        $freeETHReceiver->delete();
                    }
                }*/
            }

            return view('pages/logged-user/dentist/single-contract-view-'.$contract->status, $params);
        } else {
            return abort(404);
        }
    }

    public function getContractsView() {
        $active_contracts = TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->whereIn('status', array('active', 'awaiting-payment', 'awaiting-approval'))->orderBy('contract_active_at', 'desc')->limit(7)->get()->all();
        $pending_contracts = TemporallyContract::where(array('dentist_id' => session('logged_user')['id'], 'status' => 'pending'))->orderBy('created_at', 'desc')->limit(4)->get()->all();
        $cancelled_contracts = TemporallyContract::where(array('dentist_id' => session('logged_user')['id'], 'status' => 'cancelled'))->orderBy('cancelled_at', 'desc')->limit(4)->get()->all();
        return view('pages/logged-user/dentist/homepage-contracts', ['active_contracts' => $active_contracts, 'pending_contracts' => $pending_contracts, 'cancelled_contracts' => $cancelled_contracts]);
    }

    protected function getCreateContractView()   {
        $current_logged_dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']);
        $existing_company_registration_number = TemporallyContract::where(array('dentist_id' => $current_logged_dentist->id))->where('professional_company_number', '!=' , '')->get()->first();

        $dentist_registration_number = '';
        if ($existing_company_registration_number && $existing_company_registration_number->professional_company_number != '') {
            $dentist_registration_number = $existing_company_registration_number->professional_company_number;
        }

        $calculator_proposals = CalculatorParameter::where(array('code' => (new APIRequestsController())->getAllCountries()[$current_logged_dentist->country_id - 1]->code))->get(['param_gd_cd_id', 'param_gd_cd', 'param_gd_id', 'param_cd_id', 'param_gd', 'param_cd', 'param_id'])->first()->toArray();
        $params = ['countries' => (new APIRequestsController())->getAllCountries(), 'current_logged_dentist' => $current_logged_dentist, 'calculator_proposals' => $calculator_proposals, 'dentist_registration_number' => $dentist_registration_number];
        if (!empty(Input::get('renew-contract'))) {
            $contract = TemporallyContract::where(array('slug' => Input::get('renew-contract'), 'status' => 'cancelled'))->get()->first();
            if (!empty($contract)) {
                $params['renew_contract'] = $contract;
            } else {
                return abort(404);
            }
        }
        return view('pages/logged-user/dentist/create-contract', $params);
    }

    protected function storeAndSubmitTemporallyContract(Request $request) {
        $customMessages = [
            'professional-company-number.required' => 'Professional/Company Registration Number is required.',
            'fname.required' => 'Patient first name is required.',
            'lname.required' => 'Patient last name is required.',
            'email.required' => 'Patient email is required.',
            'general-dentistry.required' => 'Services covered are required.',
            'monthly-premium.required' => 'Monthly premium is required.',
            'check-ups-per-year.required' => 'Check ups per year are is required.',
            'teeth-cleaning-per-year.required' => 'Teeth cleaning per year are required.',
            'dentist_signature.required' => 'Dentist signature is required.',
            'address.required' => 'Wallet Address is required.',
            'address.min' => 'Wallet Address is invalid.',
        ];
        $this->validate($request, [
            'professional-company-number' => 'required|max:100',
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'email' => 'required|max:50',
            'general-dentistry' => 'required',
            'monthly-premium' => 'required',
            'check-ups-per-year' => 'required',
            'teeth-cleaning-per-year' => 'required',
            'dentist_signature' => 'required',
            'address' => 'required|min:42'
        ], $customMessages);

        $data = $request->input();
        //$files = $request->file();

        //check email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('create-contract')->with(['error' => 'Your form was not sent. Please try again with valid email.']);
        }

        //if user selected avatar or entered dcn_address both for first time
        if (!empty($data['hidden-image']) || !empty($data['address']) || !empty($data['website'])) {
            $post_fields_arr = array();
            if (!empty($data['hidden-image'])) {
                $data['image-name'] = 'user-'.time().'.png';
                $data['image-path'] = UPLOADS . $data['image-name'];
                $img_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['hidden-image']));

                file_put_contents($data['image-path'], $img_data);
                $post_fields_arr['avatar'] = curl_file_create($data['image-path'], 'image/png', $data['image-name']);
            }
            if (!empty($data['address'])) {
                $post_fields_arr['dcn_address'] = trim($data['address']);
            }
            if (!empty($data['website'])) {
                if (mb_strpos(mb_strtolower($request->input('website')), 'http') !== 0) {
                    $data['website'] = 'http://' . trim($data['website']);
                }
                $post_fields_arr['website'] = $data['website'];
            }

            //handle the API response
            $api_response = (new APIRequestsController())->updateUserData($post_fields_arr);
            if ($api_response && array_key_exists('success', $api_response) && $api_response['success']) {
                if (!empty($data['hidden-image'])) {
                    //deleting the dummy image
                    unlink($data['image-path']);
                }
            } else {
                return redirect()->route('create-contract')->with(['errors_response' => $api_response['errors']]);
            }
        }

        $random_string = $this->generateRandomString();

        //saving the dentist signature in new unique folder for this contract
        $temp_contract_folder_path = CONTRACTS . DS . $random_string;
        if (!file_exists($temp_contract_folder_path)) {
            mkdir($temp_contract_folder_path, 0777, true);

            //create image from the base64 signature
            file_put_contents($temp_contract_folder_path . DS . 'dentist-signature.png', $this->base64ToPng($data['dentist_signature']));
        } else {
            //this should never happen, but ..
            return redirect()->route('create-contract')->with(['error' => 'Something went wrong with contract creation. Please try again later.']);
        }

        $sender = (new APIRequestsController())->getUserData(session('logged_user')['id']);

        $temporally_contract = new TemporallyContract();
        $temporally_contract->dentist_id = session('logged_user')['id'];
        $temporally_contract->dentist_address = trim($data['address']);
        $temporally_contract->dentist_street_address = trim($data['postal-address']);
        $countries = (new APIRequestsController())->getAllCountries();
        $temporally_contract->dentist_country = $countries[$sender->country_id - 1]->name;
        $api_enums = (new APIRequestsController())->getAllEnums();
        $temporally_contract->dentist_name = trim($data['dentist-name']);
        $temporally_contract->dentist_email = $sender->email;
        $temporally_contract->dentist_phone = '+' . $countries[$sender->country_id - 1]->phone_code . ' ' . trim($data['phone']);
        $temporally_contract->dentist_website = trim($data['website']);
        $temporally_contract->patient_fname = trim($data['fname']);
        $temporally_contract->patient_lname = trim($data['lname']);
        $temporally_contract->patient_email = trim($data['email']);
        $temporally_contract->professional_company_number = trim($data['professional-company-number']);
        $temporally_contract->general_dentistry = serialize($data['general-dentistry']);
        $temporally_contract->monthly_premium = trim($data['monthly-premium']);
        $temporally_contract->check_ups_per_year = trim($data['check-ups-per-year']);
        $temporally_contract->teeth_cleaning_per_year = trim($data['teeth-cleaning-per-year']);
        $temporally_contract->slug = $random_string;
        $temporally_contract->save();

        if ($temporally_contract->id) {
            //send email
            $body = '<!DOCTYPE html><html><head></head><body style="font-size: 13px;"><div>Dear '.$temporally_contract->patient_fname.' '.$temporally_contract->patient_lname.',<br><br><br>I have created an individualized Assurance Contract for you. It entitles you to prevention-focused dental services against an affordable monthly premium in Dentacoin (DCN) currency*.<br><br>It’s very easy to start: just click on the button below, sign up, check my proposal and follow the instructions if you are interested:<br><br><br><a href="'.route('contract-proposal', ['slug' => $temporally_contract->slug]).'" style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE CONTRACT</a><br><br><br>Looking forward to seeing you onboard!<br><br>Regards,<br><b>'.$sender->name.'</b><br><br><br><i style="font-size: 11px;">* Dentacoin is the first dental cryptocurrency which can be earned through the Dentacoin tools, used as a means of payment for dental services and assurance fees, and exchanged to any other crypto or traditional currency.</i></div></body></html>';

            $contractRecord = new ContractRecord();
            $contractRecord->contract_id = $temporally_contract->id;
            $contractRecord->type = 'Contract creation by dentist';
            $contractRecord->save();

            Mail::send(array(), array(), function($message) use ($body, $data, $sender) {
                $message->to($data['email'])->subject('See & sign your Assurance contract');
                $message->from($sender->email, $sender->name)->replyTo($sender->email, $sender->name);
                $message->setBody($body, 'text/html');
            });

            if (count(Mail::failures()) > 0) {
                return redirect()->route('create-contract')->with(['error' => 'Something went wrong with sending contract via email. Please try again later.']);
            } else {
                return redirect()->route('dentist-contract-view', ['slug' => $temporally_contract->slug])->with(['success' => true, 'popup-html' => '<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">SUCCESSFULLY SENT.</div><div class="padding-top-20 padding-bottom-25 fs-20">Yоur contract sample was successfully sent. You will be notified via email when '.trim($data['fname']).' '.trim($data['lname']).' signs the contract.</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0)" class="white-blue-green-btn min-width-200 close-popup">OK</a></div></div>']);
            }
        } else {
            return redirect()->route('create-contract')->with(['error' => 'Something went wrong with sending contract via email. Please try again later.']);
        }
    }

    public function changeToActiveStatus($contract, $patient) {
        if((new UserController())->checkDentistSession()) {
            $dentistId = session('logged_user')['id'];
        } else {
            $dentistId = $contract->dentist_id;
        }
        $dentist = (new APIRequestsController())->getUserData($dentistId);

        $contract->status = 'active';
        $contract->is_processing = false;
        $contract->save();

        // let cronjob check know that database is synced with this transaction status
        $contractTransactionHash = contractTransactionHash::where(array('contract_slug' => $contract->slug, 'to_status' => 'active'))->get()->first();
        if (!empty($contractTransactionHash)) {
            $contractTransactionHash->synced_with_assurance_db = true;
            $contractTransactionHash->save();
        }

        $email_view = view('emails/dentist-approve-contract-on-blockchain', ['dentist' => $dentist, 'patient_name' => $patient->name, 'contract_slug' => $contract->slug, 'amount' => $contract->monthly_premium]);
        $body = $email_view->render();

        Mail::send(array(), array(), function($message) use ($body, $patient, $dentist) {
            $message->to($patient->email)->subject('Your smart Assurance contract is activated');
            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
            $message->setBody($body, 'text/html');
        });

        $contractRecord = new ContractRecord();
        $contractRecord->contract_id = $contract->id;
        $contractRecord->type = 'Contract approved by dentist';
        $contractRecord->save();
    }

    public function successfulBlockchainWithdraw($contract, $transactionHash) {
        if((new UserController())->checkDentistSession()) {
            $dentistId = session('logged_user')['id'];
        } else {
            $dentistId = $contract->dentist_id;
        }
        $dentist = (new APIRequestsController())->getUserData($dentistId);
        $dentist_name = $dentist->name;
        $patient = (new APIRequestsController())->getUserData($contract->patient_id);

        $contract->is_processing = false;
        $contract->save();

        // let cronjob check know that database is synced with this transaction status
        $contractTransactionHash = contractTransactionHash::where(array('contract_slug' => $contract->slug, 'to_status' => 'active-withdraw'))->get()->first();
        if (!empty($contractTransactionHash)) {
            $contractTransactionHash->synced_with_assurance_db = true;
            $contractTransactionHash->save();
        }

        $email_view = view('emails/dentist-successful-withdraw', ['dentist_name' => $this->prepareUserName($dentist), 'patient_name' => $patient->name, 'transaction_hash' => $transactionHash]);
        $body = $email_view->render();

        Mail::send(array(), array(), function($message) use ($body, $patient, $dentist_name) {
            $message->to($patient->email)->subject($dentist_name.' has received your payment');
            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
            $message->setBody($body, 'text/html');
        });

        $contractRecord = new ContractRecord();
        $contractRecord->contract_id = $contract->id;
        $contractRecord->type = 'Successful payment';
        $contractRecord->data = $transactionHash;
        $contractRecord->save();
    }

    //dentist can add profile description while waiting for approval from Dentacoin admin
    protected function inviteYourClinic(Request $request) {
        $data = $request->input();

        var_dump($data);
        die();
    }

    protected function takeActionForPendingContractRecords(Request $request) {
        $this->validate($request, [
            'record' => 'required',
            'action' => 'required'
        ], [
            'record.required' => 'Record is required.',
            'action.required' => 'Action is required.'
        ]);

        $recordIds = @unserialize($request->input('record'));
        if ($recordIds === false && $request->input('record') !== 'b:0;') {
            return response()->json(['error' => true, 'message' => 'Something went wrong, please try again later or contact <a href=\'mailto:assurance@dentacoin.com\'>Dentacoin team</a>.']);
        } else {
            if(!empty($recordIds)) {

                $recordEmailType = NULL;
                if(sizeof($recordIds) > 1) {
                    $recordEmailType = 'check-up and teeth cleaning appointments';
                    $recordGetTypeParam = 'check-up-and-teeth-cleaning';
                }

                foreach ($recordIds as $recordId) {
                    $record = ContractCheckup::where(array('id' => $recordId, 'status' => 'sent'))->get()->first();
                    if(!empty($record)) {
                        $contract = TemporallyContract::where(array('id' => $record->contract_id, 'dentist_id' => session('logged_user')['id']))->get()->first();
                        if(!empty($contract)) {
                            if($recordEmailType == NULL) {
                                if($record->type == 'check-up') {
                                    $recordEmailType = 'check-up appointment';
                                    $recordGetTypeParam = 'check-up';
                                } else if($record->type == 'teeth-cleaning') {
                                    $recordEmailType = 'teeth cleaning appointment';
                                    $recordGetTypeParam = 'teeth-cleaning';
                                }
                            }

                            if($request->input('action') == 'confirm') {
                                $record->status = 'approved';
                            } else if($request->input('action') == 'decline') {
                                $record->status = 'rejected';
                            }

                            $record->save();
                        } else {
                            return response()->json(['error' => true, 'message' => 'Trying to approve contract record which is not yours.']);
                        }
                    } else {
                        return response()->json(['error' => true, 'message' => 'Trying to approve missing contract check-up.']);
                    }
                }


                $patient = (new APIRequestsController())->getUserData($contract->patient_id);
                $dentist = (new APIRequestsController())->getUserData(session('logged_user')['id']);

                $contract_active_at = strtotime($contract->contract_active_at);
                $timeSinceContractSigning = (new \App\Http\Controllers\Controller())->convertMS(time() - $contract_active_at);
                $yearsActionsToBeExecuted = 1;
                if(array_key_exists('days', $timeSinceContractSigning) && $timeSinceContractSigning['days'] >= 365) {
                    $yearsActionsToBeExecuted += floor($timeSinceContractSigning['days'] / 365);
                }

                $periodBegin = date('Y-m-d', strtotime(' + ' . (365 * ($yearsActionsToBeExecuted - 1)) . ' days', $contract_active_at));
                $periodEnd = date('Y-m-d', strtotime(' + ' . (365 * $yearsActionsToBeExecuted) . ' days', $contract_active_at));

                $currentCheckups = (new \App\Http\Controllers\PatientController())->getCheckUpOrTeethCleaning('check-up', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'), true);
                $currentTeethCleanings = (new \App\Http\Controllers\PatientController())->getCheckUpOrTeethCleaning('teeth-cleaning', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'), true);

                $approvedRecordRecordsLeft = '';
                $checkUpsLabel = 'check-up';
                $teethCleaningsLabel = 'teeth cleaning';
                if($currentCheckups < $contract->check_ups_per_year && $currentTeethCleanings < $contract->teeth_cleaning_per_year) {
                    if($contract->check_ups_per_year - $currentCheckups > 1) {
                        $checkUpsLabel = 'check-ups';
                        $teethCleaningsLabel = 'teeth cleanings';
                    }
                    $approvedRecordRecordsLeft = '<br><br><br>You have ' . ($contract->check_ups_per_year - $currentCheckups) . ' more '.$checkUpsLabel.' and ' . ($contract->teeth_cleaning_per_year - $currentTeethCleanings) . ' more '.$teethCleaningsLabel.' recommended this year.';
                } else if($currentCheckups < $contract->check_ups_per_year) {
                    if($contract->check_ups_per_year - $currentCheckups > 1) {
                        $checkUpsLabel = 'check-ups';
                    }
                    $approvedRecordRecordsLeft = '<br><br><br>You have ' . ($contract->check_ups_per_year - $currentCheckups) . ' more '.$checkUpsLabel.' recommended this year.';
                } else if($currentTeethCleanings < $contract->teeth_cleaning_per_year) {
                    if($contract->teeth_cleaning_per_year - $currentTeethCleanings > 1) {
                        $teethCleaningsLabel = 'teeth cleanings';
                    }
                    $approvedRecordRecordsLeft = '<br><br><br>You have ' . ($contract->teeth_cleaning_per_year - $currentTeethCleanings) . ' more '.$teethCleaningsLabel.' recommended this year.';
                }

                if($request->input('action') == 'confirm') {
                    $subject = $this->prepareUserName($dentist) . ' confirmed your record';

                    $email_view = view('emails/dentist-approving-contract-record', ['dentist' => $dentist, 'patient_name' => $patient->name, 'type' => $recordEmailType, 'approvedRecordRecordsLeft' => $approvedRecordRecordsLeft]);
                    $emailBody = $email_view->render();
                } else if($request->input('action') == 'decline') {
                    $subject = $this->prepareUserName($dentist) . ' ' . $dentist->name . ' declined your record';

                    $email_view = view('emails/dentist-declining-contract-record', ['dentist' => $dentist, 'patient_name' => $patient->name, 'type' => $recordEmailType, 'recordGetTypeParam' => $recordGetTypeParam, 'slug' => $contract->slug]);
                    $emailBody = $email_view->render();
                }

                Mail::send(array(), array(), function($message) use ($patient, $subject, $emailBody) {
                    $message->to($patient->email)->subject($subject);
                    $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                    $message->setBody($emailBody, 'text/html');
                });

                return response()->json(['success' => true, 'data' => $recordIds]);

            } else {
                return response()->json(['error' => true, 'message' => 'Something went wrong, please try again later or contact <a href=\'mailto:assurance@dentacoin.com\'>Dentacoin team</a>.']);
            }
        }
    }

    protected function checkForPendingContractRecords(Request $request) {
        $this->validate($request, [
            'contract' => 'required'
        ], [
            'contract.required' => 'Contract is required.'
        ]);

        $contract = TemporallyContract::where(array('slug' => $request->input('contract'), 'dentist_id' => session('logged_user')['id'], 'status' => 'active'))->get()->first();
        if(!empty($contract)) {
            $records = ContractCheckup::where(array('status' => 'sent', 'contract_id' => $contract->id))->get()->all();

            $timeSinceContractSigning = (new \App\Http\Controllers\Controller())->convertMS(time() - strtotime($contract->contract_active_at));
            $yearsActionsToBeExecuted = 1;
            if(array_key_exists('days', $timeSinceContractSigning) && $timeSinceContractSigning['days'] >= 365) {
                $yearsActionsToBeExecuted += floor($timeSinceContractSigning['days'] / 365);
            }

            $periodBegin = date('Y-m-d', strtotime(' + ' . (365 * ($yearsActionsToBeExecuted - 1)) . ' days', strtotime($contract->contract_active_at)));
            $periodEnd = date('Y-m-d', strtotime(' + ' . (365 * $yearsActionsToBeExecuted) . ' days', strtotime($contract->contract_active_at)));

            $checkUp = NULL;
            $teethCleaning = NULL;
            if(!empty($records)) {
                $patient = (new APIRequestsController())->getUserData($contract->patient_id);
                $patientAvatar = '';
                if(!empty($patient->thumbnail_url)) {
                    $patientAvatar = '<figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block padding-left-10 padding-right-10"><img alt="Patient avatar" src="'.$patient->thumbnail_url.'" class="max-width-150 max-width-xs-120 width-100"/></figure>';
                }

                foreach ($records as $record) {
                    if($record->type == 'check-up' && $checkUp == NULL) {
                        $checkUp = $record;
                    } else if($record->type == 'teeth-cleaning' && $teethCleaning == NULL) {
                        $teethCleaning = $record;
                    }
                }

                if($checkUp != NULL && $teethCleaning != NULL) {
                    $currentCheckUpCount = (new PatientController())->getCheckUpOrTeethCleaning('check-up', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'), true);
                    $currentTeethCleaningsCount = (new PatientController())->getCheckUpOrTeethCleaning('teeth-cleaning', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'), true);
                    $popupHtml = '<div class="text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70 max-width-xs-40 width-100"/></figure>'.$patientAvatar.'<figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70 max-width-xs-40 width-100"/></figure></div><div class="padding-top-15 padding-bottom-10 text-center lato-bold fs-30">'.$patient->name.'</div><div class="text-center fs-22 line-height-24 max-width-500 margin-0-auto">said they\'ve visited you for a <span class="calibri-bold blue-green-color">check-up</span> on <span class="calibri-bold">'.date('d-m-Y', strtotime($checkUp->request_date_at)).'</span> and <span class="calibri-bold blue-green-color">teeth cleaning</span> on <span class="calibri-bold">'.date('d-m-Y', strtotime($teethCleaning->request_date_at)).'</span>.</div><div class="text-center padding-bottom-20 padding-top-15"><a href="javascript:void(0);" class="red-white-btn decline-record min-width-200 margin-left-10 margin-right-10 margin-top-10" data-record="'.serialize(array($checkUp->id, $teethCleaning->id)).'">Decline</a><a href="javascript:void(0);" class="white-green-btn confirm-record min-width-200 margin-left-10 margin-right-10 margin-top-10" data-record="'.serialize(array($checkUp->id, $teethCleaning->id)).'">CONFIRM</a></div>';

                    return response()->json(['success' => true, 'html' => $popupHtml, 'data' => array($checkUp->id => $checkUp, $teethCleaning->id => $teethCleaning), 'currentCheckUpCount' => $currentCheckUpCount, 'check_ups_per_year' => $contract->check_ups_per_year, 'currentTeethCleaningsCount' => $currentTeethCleaningsCount, 'teeth_cleaning_per_year' => $contract->teeth_cleaning_per_year, 'data_record' => serialize(array($checkUp->id, $teethCleaning->id))]);
                } else if($checkUp != NULL) {
                    $currentCheckUpCount = (new PatientController())->getCheckUpOrTeethCleaning('check-up', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'), true);
                    $popupHtml = '<div class="text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70 max-width-xs-40 width-100"/></figure>'.$patientAvatar.'</div><div class="padding-top-15 padding-bottom-10 text-center lato-bold fs-30">'.$patient->name.'</div><div class="text-center fs-22 line-height-24 max-width-500 margin-0-auto">said they\'ve visited you for a <span class="calibri-bold blue-green-color">check-up</span> on <span class="calibri-bold">'.date('d-m-Y', strtotime($checkUp->request_date_at)).'</span>.</div><div class="text-center padding-bottom-20 padding-top-15"><a href="javascript:void(0);" class="red-white-btn decline-record min-width-200 margin-left-10 margin-right-10 margin-top-10" data-record="'.serialize(array($checkUp->id)).'">Decline</a><a href="javascript:void(0);" class="white-green-btn confirm-record min-width-200 margin-left-10 margin-right-10 margin-top-10" data-record="'.serialize(array($checkUp->id)).'">CONFIRM</a></div>';

                    return response()->json(['success' => true, 'html' => $popupHtml, 'data' => array($checkUp->id => $checkUp), 'currentCheckUpCount' => $currentCheckUpCount, 'check_ups_per_year' => $contract->check_ups_per_year, 'data_record' => serialize(array($checkUp->id))]);
                } else if($teethCleaning != NULL) {
                    $currentTeethCleaningsCount = (new PatientController())->getCheckUpOrTeethCleaning('teeth-cleaning', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved'), true);
                    $popupHtml = '<div class="text-center">'.$patientAvatar.'<figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70 max-width-xs-40 width-100"/></figure></div><div class="padding-top-15 padding-bottom-10 text-center lato-bold fs-30">'.$patient->name.'</div><div class="text-center fs-22 line-height-24 max-width-500 margin-0-auto">said they\'ve visited you for a teeth cleaning</span> on <span class="calibri-bold">'.date('d-m-Y', strtotime($teethCleaning->request_date_at)).'</span>.</div><div class="text-center padding-bottom-20 padding-top-15"><a href="javascript:void(0);" class="red-white-btn decline-record min-width-200 margin-left-10 margin-right-10 margin-top-10" data-record="'.serialize(array($teethCleaning->id)).'">Decline</a><a href="javascript:void(0);" class="white-green-btn confirm-record min-width-200 margin-left-10 margin-right-10 margin-top-10" data-record="'.serialize(array($teethCleaning->id)).'">CONFIRM</a></div>';

                    return response()->json(['success' => true, 'html' => $popupHtml, 'data' => array($teethCleaning->id => $teethCleaning), 'currentTeethCleaningsCount' => $currentTeethCleaningsCount, 'teeth_cleaning_per_year' => $contract->teeth_cleaning_per_year, 'data_record' => serialize(array($teethCleaning->id))]);
                }
            } else {
                return response()->json(['error' => true, 'message' => 'Missing records.']);
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Missing contract.']);
        }
    }
}

