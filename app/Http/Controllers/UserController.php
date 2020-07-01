<?php

namespace App\Http\Controllers;

use App\ContractCheckup;
use App\ContractRecord;
use App\contractTransactionHash;
use App\PublicKey;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    public static function instance() {
        return new UserController();
    }

    protected function getMyContractsView()     {
        $user_data = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        if($this->checkPatientSession()) {
            return view('pages/logged-user/my-contracts', ['user_data' => $user_data, 'patient_or_not' => false, 'contracts' => TemporallyContract::where(array('patient_id' => session('logged_user')['id']))->orWhere(array('patient_email' => $user_data->email))->get()->sortByDesc('created_at')]);
        } else if($this->checkDentistSession()) {
            if(!empty(Input::get('status'))) {
                //if get parameter is passed query by status
                return view('pages/logged-user/my-contracts', ['user_data' => $user_data, 'patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id'], 'status' => Input::get('status')))->get()->sortByDesc('created_at')]);
            }
            return view('pages/logged-user/my-contracts', ['user_data' => $user_data, 'patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->sortByDesc('created_at')]);
        }
    }

    protected function getMyContractsTemplate()     {
        $user_data = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        if($this->checkPatientSession()) {
            return view('pages/logged-user/my-contracts-template', ['user_data' => $user_data, 'patient_or_not' => false, 'contracts' => TemporallyContract::where(array('patient_id' => session('logged_user')['id']))->orWhere(array('patient_email' => $user_data->email))->get()->sortByDesc('created_at')]);
        } else if($this->checkDentistSession()) {
            if(!empty(Input::get('status'))) {
                //if get parameter is passed query by status
                return view('pages/logged-user/my-contracts-template', ['user_data' => $user_data, 'patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id'], 'status' => Input::get('status')))->get()->sortByDesc('created_at')]);
            }
            return view('pages/logged-user/my-contracts-template', ['user_data' => $user_data, 'patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->sortByDesc('created_at')]);
        }
    }

    protected function getAddressValidationOrRememberMe(Request $request) {
        $view = view('partials/address-validation-or-remember-me', ['cache' => $request->input('cache')]);
        $view = $view->render();
        return response()->json(['success' => $view]);
    }

    protected function getKeystoreFilePasswordValidation() {
        $view = view('partials/keystore-file-password-validation');
        $view = $view->render();
        return response()->json(['success' => $view]);
    }

    protected function getPopupCancelContract(Request $request) {
        $contract = TemporallyContract::where(array('slug' => $request->input('contract')))->get()->first();
        if(!empty($contract)) {
            if($this->checkDentistSession()) {
                $type = 'dentist';
                if(!empty($contract->patient_id)) {
                    $receiver_name = (new APIRequestsController())->getUserData($contract->patient_id)->name;
                } else {
                    $receiver_name = $contract->patient_fname . ' ' . $contract->patient_lname;
                }
            } else if($this->checkPatientSession()) {
                $type = 'patient';
                $receiver_name = (new APIRequestsController())->getUserData($contract->dentist_id)->name;
            }
            $view = view('partials/popup-cancel-contract', ['receiver_name' => $receiver_name, 'type' => $type, 'contract_status' => $contract->status]);
            $view = $view->render();
            return response()->json(['success' => $view]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    protected function getContractData(Request $request) {
        $contract = TemporallyContract::where(array('slug' => $request->input('contract')))->get()->first();
        if($contract) {
            //check if user trying to cheat
            if($contract->patient_id != session('logged_user')['id'] && $contract->dentist_id != session('logged_user')['id']) {
                return response()->json(['error' => 'Wrong contract.']);
            }

            $contract_data = array(
                /*'patient' => $patient_data->patient_address,
                'dentist' => $dentist_data->dentist_address,*/
                'patient' => $contract->patient_address,
                'dentist' => $contract->dentist_address,
                'value_usd' => $contract->monthly_premium,
                'status' => $contract->status,
                'date_start_contract' => strtotime($contract->contract_active_at),
                'contract_ipfs_hash' => $contract->document_hash
            );

            return response()->json(['success' => true, 'contract_data' => $contract_data]);
        } else {
            return response()->json(['error' => 'Transaction failed, please try again later.']);
        }
    }

    protected function getRecipePopup(Request $request) {
        /*$current_user_data = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        if(empty($current_user_data->dcn_address)) {
            return response()->json(['error' => 'You cannot execute blockchain transactions without having your Wallet Address saved in your profile. Please save your Wallet Address in your profile and try again.']);
        }*/

        $contract = TemporallyContract::where(array('slug' => $request->input('contract')))->get()->first();
        if($contract) {
            //check if user trying to cheat
            if($contract->patient_id != session('logged_user')['id'] && $contract->dentist_id != session('logged_user')['id']) {
                return response()->json(['error' => 'Wrong contract.']);
            }

            //$current_logged_user_data = (new APIRequestsController())->getUserData(session('logged_user')['id']);
            if($this->checkDentistSession()) {
                $type = 'dentist';
                $currentTransactionInitiatorAddress = $contract->dentist_address;
                $patient_data = (new APIRequestsController())->getUserData($contract->patient_id);
            } else if($this->checkPatientSession()) {
                $type = 'patient';
                $currentTransactionInitiatorAddress = $contract->patient_address;
                /*$dentist_data = (new APIRequestsController())->getUserData($contract->dentist_id);
                $patient_data = $current_logged_user_data;*/
            }

            $params = [/*'current_logged_user' => $current_logged_user_data, */'currentTransactionInitiatorAddress' => $currentTransactionInitiatorAddress, 'cached_key' => $request->input('cached_key'), 'show_dcn_bar' => $request->input('show_dcn_bar'), 'recipe_title' => $request->input('recipe_title'), 'recipe_subtitle' => $request->input('recipe_subtitle'), 'recipe_checkbox_text' => $request->input('recipe_checkbox_text'), 'btn_label' => $request->input('btn_label')];

            $sent_eth_to_dentist = $request->input('sent_eth_to_dentist');
            if(!empty($sent_eth_to_dentist)) {
                $params['sent_eth_to_dentist'] = true;
            }

            $withdrawableDCN = $request->input('withdrawableDCN');
            if(!empty($withdrawableDCN)) {
                $params['withdrawableDCN'] = $withdrawableDCN;
            }

            $withdrawableUSD = $request->input('withdrawableUSD');
            if(!empty($withdrawableUSD)) {
                $params['withdrawableUSD'] = $withdrawableUSD;
            }

            $requestType = $request->input('type');
            if(!empty($requestType)) {
                if($requestType == 'qr-scan') {
                    $params['qr_scan'] = true;
                }
            }

            $view = view('partials/transaction-recipe-popup', $params);
            $view = $view->render();
            $contract_data = array(
                /*'patient' => $patient_data->patient_address,
                'dentist' => $dentist_data->dentist_address,*/
                'patient' => $contract->patient_address,
                'dentist' => $contract->dentist_address,
                'value_usd' => $contract->monthly_premium,
                'status' => $contract->status,
                'date_start_contract' => strtotime($contract->contract_active_at),
                'contract_ipfs_hash' => $contract->document_hash,
                'type' => $type
            );

            if (!empty($patient_data)) {
                $contract_data['patient_name'] = $patient_data->name;
            }

            return response()->json(['success' => $view, 'contract_data' => $contract_data/*, 'dcn_address' => $current_user_data->dcn_address*/]);
        } else {
            return response()->json(['error' => 'Transaction failed, please try again later.']);
        }
    }

    function checkEmailAndReturnData(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'type' => 'required',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be valid.',
            'type.email' => 'Type is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $patientData = (new APIRequestsController())->getUserByEmailAndType($request->input('email'), $request->input('type'));
        if(!empty($patientData)) {
            return response()->json(['success' => true, 'data' => $patientData[0]->name]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    function checkCaptcha(Request $request) {
        $temp_save_captcha_session = session('captcha');
        //saving the session again, because theres bug in the captcha library

        if (captcha_check($request->input('captcha'))) {
            session(['captcha' => $temp_save_captcha_session]);
            return response()->json(['success' => true]);
        } else {
            session(['captcha' => $temp_save_captcha_session]);
            return response()->json(['error' => true]);
        }
    }

    public function checkSession()   {
        if(!empty(session('logged_user')) && (session('logged_user')['type'] == 'patient' || session('logged_user')['type'] == 'dentist'))    {
            //LOGGED
            return true;
        }else {
            //NOT LOGGED
            return false;
        }
    }

    public function checkDentistSession()   {
        if(!empty(session('logged_user')) && session('logged_user')['type'] == 'dentist')    {
            //LOGGED
            return true;
        }else {
            //NOT LOGGED
            return false;
        }
    }

    public function checkPatientSession()   {
        if(!empty(session('logged_user')) && session('logged_user')['type'] == 'patient')    {
            //LOGGED
            return true;
        }else {
            //NOT LOGGED
            return false;
        }
    }

    protected function userLogout(Request $request) {
        $route = '';
        $token = $this->encrypt(session('logged_user')['token'], getenv('API_ENCRYPTION_METHOD'), getenv('API_ENCRYPTION_KEY'));
        if($request->session()->has('logged_user'))    {
            if(isset(session('logged_user')['type'])) {
                if(session('logged_user')['type'] == 'dentist') {
                    $route = 'home';
                } else if(session('logged_user')['type'] == 'patient') {
                    $route = 'patient-access';
                }
                $request->session()->forget('logged_user');
                return redirect()->route($route)->with(['logout_token' => $token]);
            } else {
                return redirect()->route('home')->with(['logout_token' => $token]);
            }
        }
        return redirect()->route('home');
    }

    protected function updateContractStatus(Request $request) {
        $data = $this->clearPostData($request->input());
        $response = array();
        $contract = TemporallyContract::where(array('slug' => $data['contract']))->get()->first();
        if($contract) {
            $response['success'] = true;
            if($this->checkDentistSession()) {
                $response['path'] = 'dentist';
                $type = 'dentist';
            } else if($this->checkPatientSession()) {
                $response['path'] = 'patient';
                $type = 'patient';
            }

            $initiator = (new APIRequestsController())->getUserData(session('logged_user')['id']);

            $this->changeToCancelledStatus($contract, $type, $initiator, $data['reason'], $data['comments']);

            echo json_encode($response);
            die();
        } else {
            $response['error'] = 'Cancellation failed, wrong contract.';
            echo json_encode($response);
            die();
        }
    }

    public function changeToCancelledStatus($contract, $type, $initiator, $reason, $comments = null) {
        if($type == 'dentist') {
            $cancellation_reason = array(
                'reason' => $reason
            );

            if(!empty($comments)) {
                $cancellation_reason['comments'] = $comments;
            }

            $contract->status = 'cancelled';
            $contract->cancelled_at = new \DateTime();
            $contract->cancellation_reason = serialize($cancellation_reason);
            $contract->is_processing = false;
            $contract->save();

            if(!empty($contract->patient_id)) {
                $patient = (new APIRequestsController())->getUserData($contract->patient_id);
                $patient_name = $patient->name;
                $patient_email = $patient->email;

                // let cronjob check know that database is synced with this transaction status
                $transactionHash = contractTransactionHash::where(array('contract_slug' => $contract->slug, 'to_status' => 'cancelled'))->get()->first();
                if (!empty($transactionHash)) {
                    $transactionHash->synced_with_assurance_db = true;
                    $transactionHash->save();
                }
            } else {
                $patient_name = $contract->patient_fname . ' ' . $contract->patient_lname;
                $patient_email = $contract->patient_email;
            }

            $email_view = view('emails/dentist-cancel-contract', ['dentist_name' => $this->prepareUserName($initiator), 'patient_name' => $patient_name, 'reason' => $reason, 'contract_slug' => $contract->slug]);
            $body = $email_view->render();

            Mail::send(array(), array(), function($message) use ($body, $patient_email, $initiator) {
                $message->to($patient_email)->subject($this->prepareUserName($initiator) . ' has cancelled your contract');
                $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                $message->setBody($body, 'text/html');
            });

            $initiator_email_view = view('emails/notifier-for-successfully-contract-cancel', ['initiator' => $this->prepareUserName($initiator), 'opposite_side' => $patient_name, 'url' => route('dentist-contract-view', ['slug' => $contract->slug]), 'reason' => $reason]);
            $initiator_body = $initiator_email_view->render();

            Mail::send(array(), array(), function($message) use ($initiator_body, $initiator, $patient_name) {
                $message->to($initiator->email)->subject('Successfully Cancelled Contract with ' . $patient_name);
                $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                $message->setBody($initiator_body, 'text/html');
            });
        } else if($type == 'patient') {
            $cancellation_reason = array(
                'reason' => $reason
            );

            if(!empty($comments)) {
                $cancellation_reason['comments'] = $comments;
            }

            $contract->status = 'cancelled';
            $contract->cancelled_at = new \DateTime();
            $contract->cancellation_reason = serialize($cancellation_reason);
            $contract->is_processing = false;
            $contract->save();

            if(!empty($contract->patient_id)) {
                // let cronjob check know that database is synced with this transaction status
                $transactionHash = contractTransactionHash::where(array('contract_slug' => $contract->slug, 'to_status' => 'cancelled'))->get()->first();
                if (!empty($transactionHash)) {
                    $transactionHash->synced_with_assurance_db = true;
                    $transactionHash->save();
                }
            }

            $dentist = (new APIRequestsController())->getUserData($contract->dentist_id);
            $email_view = view('emails/patient-cancel-contract', ['dentist' => $dentist, 'patient_name' => $initiator->name, 'reason' => $reason, 'contract_slug' => $contract->slug]);
            $body = $email_view->render();

            Mail::send(array(), array(), function($message) use ($body, $dentist, $initiator) {
                $message->to($dentist->email)->subject($initiator->name . ' has cancelled their contract');
                $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                $message->setBody($body, 'text/html');
            });

            $initiator_email_view = view('emails/notifier-for-successfully-contract-cancel', ['initiator' => $this->prepareUserName($initiator), 'opposite_side' => $this->prepareUserName($dentist), 'url' => route('patient-contract-view', ['slug' => $contract->slug]), 'reason' => $reason]);
            $initiator_body = $initiator_email_view->render();

            Mail::send(array(), array(), function($message) use ($initiator_body, $initiator, $dentist) {
                $message->to($initiator->email)->subject('Successfully Cancelled Contract with ' . $this->prepareUserName($dentist));
                $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                $message->setBody($initiator_body, 'text/html');
            });
        }

        $contractRecord = new ContractRecord();
        $contractRecord->contract_id = $contract->id;
        if($type == 'dentist') {
            $contractRecord->type = 'Contract cancelled by dentist';
        } else if($type == 'patient') {
            $contractRecord->type = 'Contract cancelled by patient';
        }

        $contractRecord->save();
    }

    public function checkIfWeHavePublicKeyOfAddress($address) {
        $public_key = PublicKey::where(array('address' => $address))->get(['public_key'])->first();
        if($public_key) {
            return $public_key->public_key;
        } else {
            return false;
        }
    }

    protected function updatePublicKeys(Request $request) {
        $this->validate($request, [
            'address' => 'required',
            'public_key' => 'required'
        ], [
            'address.required' => 'Address is required.',
            'public_key.required' => 'Public key is required.'
        ]);
        $data = $this->clearPostData($request->input());

        //if this address is missing in our local db just add it
        $existing_public_key = PublicKey::where(array('address' => $data['address']))->get()->first();
        if(!$existing_public_key) {
            $public_key = new PublicKey();
            $public_key->address = $data['address'];
            $public_key->public_key = $data['public_key'];
            $public_key->save();

        }

        echo json_encode(array('success' => true));
        die();
    }

    protected function checkPublicKey(Request $request) {
        $this->validate($request, [
            'address' => 'required'
        ], [
            'address.required' => 'Address is required.'
        ]);
        $data = $this->clearPostData($request->input());
        $response = array();

        $public_key = PublicKey::where(array('address' => $data['address']))->get()->first();
        if(!empty($public_key)) {
            $response['success'] = true;
        } else {
            $response['error'] = true;
        }
        echo json_encode($response);
        die();
    }

    protected function readAndDecryptIPFSZip(Request $request) {
        $this->validate($request, [
            'ipfs_hash' => 'required',
            'type' => 'required'
        ], [
            'ipfs_hash.required' => 'IPFS hash is required.',
            'type.required' => 'Type is required.'
        ]);

        //$data = $this->clearPostData($request);
        $response = array();

        $folder_path = ZIP_EXTRACTS . $request->input('ipfs_hash') . '-' . time();
        if (!file_exists($folder_path)) {
            try {
                mkdir($folder_path, 0777, true);
                file_put_contents($folder_path . DS . $request->input('ipfs_hash') . '.zip', fopen('http://ipfs.io/ipfs/' . $request->input('ipfs_hash'), 'r'));

                $zipper = new \Chumper\Zipper\Zipper;
                $zipper->make($folder_path . DS . $request->input('ipfs_hash') . '.zip')->extractTo($folder_path);
                $zipper->close();

                if($request->input('type') == 'dentist') {
                    $file_name = 'dentist-pdf-file.pdf';
                } else if($request->input('type') == 'patient') {
                    $file_name = 'patient-pdf-file.pdf';
                }

                $parser = new \Smalot\PdfParser\Parser();
                $response['success'] = str_replace(PHP_EOL, '', $parser->parseFile($folder_path . DS . $file_name)->getText());

                //delete the temp file in zip-contracts
                array_map('unlink', glob($folder_path . '/*.*'));
                rmdir($folder_path);

                echo json_encode($response);
                die();
            } catch(Exception $e) {
                $response['error'] = 'Something went wrong, please try again later.';
                echo json_encode($response);
                die();
            }
        } else {
            $response['error'] = 'Please wait few seconds and try again.';
            echo json_encode($response);
            die();
        }
    }

    protected function renderPdf(Request $request) {
        $this->validate($request, [
            'pdf_data' => 'required'
        ], [
            'pdf_data.required' => 'PDF data is required.'
        ]);

        $view_start = view('partials/pdf-contract-layout-start');
        $html_start = $view_start->render();
        $view_end = view('partials/pdf-contract-layout-end');
        $html_end = $view_end->render();
        $dompdf = new DOMPDF();
        $data = $html_start . html_entity_decode($request->input('pdf_data')) . $html_end;

        $dompdf->load_html($data);

        $dompdf->render();
        $dompdf->stream('contract.pdf', array('Attachment' => false));
        die();
    }

    protected function filterMyContracts(Request $request) {
        $view_params = array();
        if(!empty($request->input('filter_arr'))) {
            //FILTERING
            $view_params['patient_or_not'] = true;
            if($this->checkPatientSession()) {
                $view_params['contracts'] = TemporallyContract::where(function ($query) {
                    $query->where(array('patient_id' => session('logged_user')['id']))
                        ->orWhere(array('patient_email' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->email));
                })->whereIn('status', $request->input('filter_arr'))->get()->sortByDesc('contract_active_at');
            } else if($this->checkDentistSession()) {
                $view_params['contracts'] = TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->whereIn('status', $request->input('filter_arr'))->orderBy('contract_active_at', 'desc')->get()->all();
            }
        } else {
            //SELECTING ALL
            $view_params['patient_or_not'] = false;
            if($this->checkPatientSession()) {
                $view_params['contracts'] = TemporallyContract::where(array('patient_id' => session('logged_user')['id']))->orWhere(array('patient_email' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->email))->get()->sortByDesc('contract_active_at');
            } else if($this->checkDentistSession()) {
                $view_params['contracts'] = TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->sortByDesc('contract_active_at');
            }
        }

        $view = view('partials/table-my-contracts', $view_params);
        $view = $view->render();

        return response()->json(['success' => $view]);
    }

    public function getCountryNameById($id) {
        $countries = (new APIRequestsController())->getAllCountries();
        return $countries[$id - 1]->name;
    }

    public function getCountryPhoneCodeById($id) {
        $countries = (new APIRequestsController())->getAllCountries();
        return $countries[$id - 1]->phone_code;
    }

    protected function manageCustomCookie(Request $request) {
        if(!empty(Input::get('slug')) && !empty(Input::get('type')) && !empty(Input::get('token'))) {
            //logging
            $slug = $this->decrypt(Input::get('slug'));
            $type = $this->decrypt(Input::get('type'));
            $token = $this->decrypt(Input::get('token'));

            $user = (new APIRequestsController())->getUserData($slug);
            if($user) {
                $approved_statuses = array('approved','test','added_by_clinic_claimed','added_by_dentist_claimed');
                if($user->self_deleted != NULL) {
                    return abort(404);
                } else if(!in_array($user->status, $approved_statuses)) {
                    return abort(404);
                } else {
                    $session_arr = [
                        'token' => $token,
                        'id' => $slug,
                        'type' => $type
                    ];

                    session(['logged_user' => $session_arr]);
                    return redirect()->route('home');
                }
            } else {
                return abort(404);
            }
        } else if(!empty(Input::get('logout-token'))) {
            //logging out
            $token = $this->decrypt(Input::get('logout-token'));

            if(session('logged_user')['token'] == $token) {
                $request->session()->forget('logged_user');

                return redirect()->route('home');
            }
        } else {
            return abort(404);
        }
    }


    // method for cancelling contracts on single page contract visiting. This method is built, because we cannot have the cronjob running every seconds and sometimes users might see contract that have to be cancelled as active one
    public function automaticContractCancel($contract, $onBlockchain = true) {
        if($onBlockchain) {
            // UPGRADE THIS WITH web3 - make the check here if the contract should be cancelled and then cancel it

            // or

            // make the check on javascript once page is loaded

            /*$gasPrice = (int)(new APIRequestsController())->getGasEstimationFromEthgasstation();
            $cancelContractParams = array(
                'patient_address' => $contract->patient_address,
                'dentist_address' => $contract->dentist_address,
                'gas_price' => $gasPrice
            );

            $check_if_legit_contract = (new APIRequestsController())->cancelIfLatePayment(hash(getenv('HASHING_METHOD'), getenv('SECRET_PASSWORD').json_encode($cancelContractParams)), $contract->patient_address, $contract->dentist_address, $gasPrice);
            if(is_object($check_if_legit_contract) && property_exists($check_if_legit_contract, 'success') && $check_if_legit_contract->success) {
                //IF NORMAL PERIOD AND GRACE PERIOD PASSED CANCEL THIS CONTRACT
                $cancellation_reason = array(
                    'reason' => 'Late payment from patient.'
                );
                $contract->status = 'cancelled';
                $contract->cancelled_at = new \DateTime();
                $contract->cancellation_reason = serialize($cancellation_reason);
                $contract->save();

                $this->sendNotificationEmailToDentistAndPatientAboutContractCancelling($contract, $cancellation_reason);
            }*/
        } else {
            if(!empty($contract->contract_active_at)) {
                // awaiting-payment

                // if contract has not been funded for the withdraw period + the grace period => cancel the contract
                if(time() > strtotime($contract->contract_active_at. ' + '.(DAYS_CONTRACT_WITHDRAWAL_PERIOD + GRACE_PERIOD).' days')) {
                    $cancellation_reason = array(
                        'reason' => 'Late payment from patient.'
                    );
                    $contract->status = 'cancelled';
                    $contract->cancelled_at = new \DateTime();
                    $contract->cancellation_reason = serialize($cancellation_reason);
                    $contract->save();

                    $this->sendNotificationEmailToDentistAndPatientAboutContractCancelling($contract, $cancellation_reason);
                }
            }/* else {
                // pending
                if(time() > strtotime($contract->created_at->toDateTimeString(). ' + '.DAYS_ACTIVE_CONTRACT_PROPOSAL.' days')) {
                    $cancellation_reason = array(
                        'reason' => 'Patient didn\'t sign contract.'
                    );
                    $contract->status = 'cancelled';
                    $contract->cancelled_at = new \DateTime();
                    $contract->cancellation_reason = serialize($cancellation_reason);
                    $contract->save();

                    $this->sendNotificationEmailToDentistAndPatientAboutContractCancelling($contract, $cancellation_reason);
                }
            }*/
        }

        return $contract;
    }

    // contracts being cancelled from cronjob which is checking for contracts that have to be cancelled
    protected function cancelContracts(Request $request) {
        $this->validate($request, [
            'contractsToBeCancelled' => 'required|array',
            'hash' => 'required',
        ], [
            'contractsToBeCancelled.required' => 'contractsToBeCancelled is required.',
            'hash.required' => 'Hash is required.',
            'contractsToBeCancelled.array' => 'contractsToBeCancelled must be array.',
        ]);

        $contractsToBeCancelled = $request->input('contractsToBeCancelled');
        if(sizeof($contractsToBeCancelled) > 0) {
            if(hash(getenv('HASHING_METHOD'), getenv('SECRET_PASSWORD').json_encode($contractsToBeCancelled)) == $request->input('hash')) {
                $alreadyCancelledContracts = array();
                foreach($contractsToBeCancelled as $contractSlug) {
                    $contract = TemporallyContract::where(array('slug' => $contractSlug))->get()->first();
                    if(!empty($contract)) {
                        if($contract->status == 'cancelled') {
                            array_push($alreadyCancelledContracts, $contract->slug);
                        } else {
                            $cancellation_reason = array(
                                'reason' => 'Patient didn\'t sign contract.'
                            );
                            $contract->status = 'cancelled';
                            $contract->cancelled_at = new \DateTime();
                            $contract->cancellation_reason = serialize($cancellation_reason);
                            $contract->save();

                            $this->sendNotificationEmailToDentistAndPatientAboutContractCancelling($contract, $cancellation_reason);
                        }
                    }
                }

                $response = ['success' => true];
                if(sizeof($alreadyCancelledContracts) > 0) {
                    $response['already-cancelled-contracts'] = $alreadyCancelledContracts;
                }

                return response()->json($response);
            } else {
                return response()->json([
                    'error' => true,
                    'data' => hash(getenv('HASHING_METHOD'), getenv('SECRET_PASSWORD').json_encode($contractsToBeCancelled)),
                    'data1' => $request->input('hash'),
                    'message' => 'False hash.'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => '$contractsToBeCancelled has 0 length.'
            ]);
        }
    }

    protected function checkContractStatus(Request $request) {
        $this->validate($request, [
            'contract' => 'required',
            'currentStatus' => 'required',
        ], [
            'contract.required' => 'contract is required.',
            'currentStatus.required' => 'currentStatus is required.'
        ]);

        if($this->checkPatientSession()) {
            $contract = TemporallyContract::where(array('patient_id' => session('logged_user')['id'], 'slug' => $request->input('contract')))->get()->first();
        } else if($this->checkDentistSession()) {
            $contract = TemporallyContract::where(array('dentist_id' => session('logged_user')['id'], 'slug' => $request->input('contract')))->get()->first();
        }

        if(!empty($contract)) {
            if($contract->status != $request->input('currentStatus')) {
                return response()->json([
                    'success' => true
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Contract status not yet changed.'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Not existing contract.'
            ]);
        }
    }

    protected function checkContractSigning(Request $request) {
        $this->validate($request, [
            'slug' => 'required',
            'to_status' => 'required',
        ], [
            'slug.required' => 'Slug is required.',
            'to_status.required' => 'Status is required.'
        ]);

        $transaction = contractTransactionHash::where(array('contract_slug' => $request->input('slug'), 'to_status' => $request->input('to_status'), 'wallet_signed' => true, 'notified_front_end' => false))->get()->first();

        if(!empty($transaction)) {
            $contract = TemporallyContract::where(array('slug' => $transaction->contract_slug))->get()->first();

            $transaction->notified_front_end = true;
            $transaction->save();

            return response()->json([
                'success' => true,
                'patient_name' => $contract->patient_full_name,
                'transactionHash' => $transaction->transactionHash
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Not existing contract.'
            ]);
        }
    }

    protected function sendNotificationEmailToDentistAndPatientAboutContractCancelling($contract, $cancellation_reason) {
        // sending email to dentist
        $dentist = (new APIRequestsController())->getUserData($contract->dentist_id);
        $email_view = view('emails/dentacoin-cancel-contract', ['user_name' => $this->prepareUserName($dentist), 'user_type' => 'dentist', 'reason' => $cancellation_reason['reason'], 'contract_slug' => $contract->slug]);
        $body = $email_view->render();

        Mail::send(array(), array(), function($message) use ($body, $dentist) {
            $message->to($dentist->email)->subject('Your Assurance contract has cancelled');
            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
            $message->setBody($body, 'text/html');
        });

        // sending email to patient
        if(!empty($contract->patient_id)) {
            $patient = (new APIRequestsController())->getUserData($contract->patient_id);
            $patient_name = $patient->name;
            $patient_email = $patient->email;
        } else {
            $patient_name = $contract->patient_fname . ' ' . $contract->patient_lname;
            $patient_email = $contract->patient_email;
        }
        $email_view = view('emails/dentacoin-cancel-contract', ['user_name' => $patient_name, 'user_type' => 'patient', 'reason' => $cancellation_reason['reason'], 'contract_slug' => $contract->slug]);
        $body = $email_view->render();

        Mail::send(array(), array(), function($message) use ($body, $patient_email) {
            $message->to($patient_email)->subject('Your Assurance contract has cancelled');
            $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
            $message->setBody($body, 'text/html');
        });
    }

    protected function handleApiEndpoints($slug, Request $request) {
        switch ($slug) {
            case 'wallets-one-week-behind':
                return DB::table('public_keys')->where('created_at', '>', date('Y-m-d', strtotime('-7 days')))->count();
                break;
            case 'wallets':
                return DB::table('public_keys')->count();
                break;
            case 'get-not-cancelled-contracts':
                $contracts = TemporallyContract::select('id', 'slug', 'patient_address', 'dentist_address', 'contract_active_at', 'created_at', 'status', 'check_ups_per_year', 'teeth_cleaning_per_year')->whereIn('status', array('awaiting-payment', 'awaiting-approval', 'active', 'pending'))->get()->all();
                foreach($contracts as $contract) {
                    if($contract->status == 'active') {
                        $timeSinceContractSigning = (new \App\Http\Controllers\Controller())->convertMS(time() - strtotime($contract->contract_active_at));
                        $yearsActionsToBeExecuted = 1;
                        // if 1 year passed since contract signing
                        if(array_key_exists('day', $timeSinceContractSigning) && $timeSinceContractSigning['day'] >= 365) {
                            $yearsActionsToBeExecuted += floor($timeSinceContractSigning['day'] / 365);

                            $periodBegin = date('Y-m-d H:i:s', strtotime(' + ' . (365 * ($yearsActionsToBeExecuted - 1)) . ' days', strtotime($contract->contract_active_at)));
                            $periodEnd = date('Y-m-d H:i:s', strtotime(' + ' . (365 * $yearsActionsToBeExecuted) . ' days', strtotime($contract->contract_active_at)));

                            $previosPeriodBegin = date('Y-m-d H:i:s', strtotime($periodBegin . ' - 365 days'));
                            $previosPeriodEnd = date('Y-m-d H:i:s', strtotime($periodEnd . ' - 365 days'));

                            //$contract->check_ups = ContractCheckup::where(array('contract_id' => $contract->id, 'type' => 'check-up', 'status' => 'approved'))->whereBetween('request_date_at', array($periodBegin, $periodEnd))->get()->all();

                            //$contract->teeth_cleanings = ContractCheckup::where(array('contract_id' => $contract->id, 'type' => 'teeth-cleaning', 'status' => 'approved'))->whereBetween('request_date_at', array($periodBegin, $periodEnd))->get()->all();

                            $contract->previos_period_check_ups = ContractCheckup::where(array('contract_id' => $contract->id, 'type' => 'check-up', 'status' => 'approved'))->whereBetween('request_date_at', array($previosPeriodBegin, $previosPeriodEnd))->get()->all();

                            //$contract->previos_period_teeth_cleanings = ContractCheckup::where(array('contract_id' => $contract->id, 'type' => 'teeth-cleaning', 'status' => 'approved'))->whereBetween('request_date_at', array($previosPeriodBegin, $previosPeriodEnd))->get()->all();
                        }
                    }

                    unset($contract->id);
                }

                if(!empty($contracts)) {
                    return response()->json([
                        'success' => true,
                        'data' => $contracts
                    ]);
                } else {
                    return response()->json([
                        'error' => true
                    ]);
                }
                break;
            case 'get-user-data-for-email-reminders':
                $this->validate($request, [
                    'hash' => 'required',
                    'ipfsHash' => 'required'
                ], [
                    'hash.required' => 'Hash is required.',
                    'ipfsHash.required' => 'ipfsHash is required.'
                ]);

                if(hash(getenv('HASHING_METHOD'), getenv('SECRET_PASSWORD').json_encode(array('ipfsHash' => $request->input('ipfsHash')))) == $request->input('hash')) {
                    $contract = TemporallyContract::where(array('document_hash' => $request->input('ipfsHash')))->get()->first();
                    if(!empty($contract)) {
                        $dentist = (new APIRequestsController())->getUserData($contract->dentist_id);

                        if(!empty($contract->patient_id)) {
                            $patient = (new APIRequestsController())->getUserData($contract->patient_id);
                            $patient_name = $patient->name;
                            $patient_email = $patient->email;
                        } else {
                            $patient_name = $contract->patient_fname . ' ' . $contract->patient_lname;
                            $patient_email = $contract->patient_email;
                        }

                        return response()->json([
                            'success' => true,
                            'dentist_name' => $dentist->name,
                            'dentist_email' => $dentist->email,
                            'patient_name' => $patient_name,
                            'patient_email' => $patient_email,
                            'slug' => $contract->slug
                        ]);
                    } else {
                        return response()->json(['error' => true, 'message' => 'Missing contract.']);
                    }
                } else {
                    return response()->json(['error' => true, 'message' => 'False hash.']);
                }
                break;
            case 'actDBts':
                $this->validate($request, [
                    'hash' => 'required',
                    'time' => 'required'
                ], [
                    'hash.required' => 'Hash is required.',
                    'time.required' => 'Time is required.'
                ]);

                if(hash(getenv('HASHING_METHOD'), getenv('SECRET_PASSWORD').json_encode(array('time' => $request->input('time')))) == $request->input('hash')) {
                    $contracts = TemporallyContract::select('slug', 'contract_active_at', 'check_ups_per_year', 'teeth_cleaning_per_year', 'patient_id')->where(array('status' => 'active'))->get()->all();
                    if(!empty($contracts)) {
                        foreach($contracts as $contract) {
                            $timeSinceContractSigning = (new \App\Http\Controllers\Controller())->convertMS(time() - strtotime($contract->contract_active_at));
                            $yearsActionsToBeExecuted = 1;
                            // if 1 year passed since contract signing
                            if(array_key_exists('day', $timeSinceContractSigning)/* && $timeSinceContractSigning['day'] >= 365*/) {
                                $yearsActionsToBeExecuted += floor($timeSinceContractSigning['day'] / 365);

                                $periodBegin = date('Y-m-d H:i:s', strtotime(' + ' . (365 * ($yearsActionsToBeExecuted - 1)) . ' days', strtotime($contract->contract_active_at)));
                                $periodEnd = date('Y-m-d H:i:s', strtotime(' + ' . (365 * $yearsActionsToBeExecuted) . ' days', strtotime($contract->contract_active_at)));

                                $contract->periodBeginTimestamp = strtotime($periodBegin);
                                $contract->periodEndTimestamp = strtotime($periodEnd);

                                //$previosPeriodBegin = date('Y-m-d H:i:s', strtotime($periodBegin . ' - 365 days'));
                                //$previosPeriodEnd = date('Y-m-d H:i:s', strtotime($periodEnd . ' - 365 days'));

                                $contract->check_ups = ContractCheckup::where(array('contract_id' => $contract->id, 'type' => 'check-up', 'status' => 'approved'))->whereBetween('request_date_at', array($periodBegin, $periodEnd))->get()->all();

                                $contract->teeth_cleanings = ContractCheckup::where(array('contract_id' => $contract->id, 'type' => 'teeth-cleaning', 'status' => 'approved'))->whereBetween('request_date_at', array($periodBegin, $periodEnd))->get()->all();

                                //$contract->previos_period_check_ups = ContractCheckup::where(array('contract_id' => $contract->id, 'type' => 'check-up', 'status' => 'approved'))->whereBetween('request_date_at', array($previosPeriodBegin, $previosPeriodEnd))->get()->all();
                            }

                            $patient = (new APIRequestsController())->getUserData($contract->patient_id);

                            $contract->patient_name = $patient->name;
                            $contract->patient_email = $patient->email;

                            unset($contract->patient_id);
                        }

                        return response()->json([
                            'success' => true,
                            'data' => $contracts
                        ]);
                    } else {
                        return response()->json(['error' => true, 'message' => 'Missing contracts.']);
                    }
                } else {
                    return response()->json(['error' => true, 'message' => 'False hash.']);
                }
                break;
            default:
                return abort(404);
        }
    }

    protected function saveAddress(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'address' => 'required|min:42'
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name is can be with maximum 50 length.',
            'address.min' => 'Wallet Address is not valid.',
            'address.required' => 'Wallet Address is required.'
        ]);
        $data = $this->clearPostData($request->input());

        $saveAddressResponse = (new APIRequestsController())->saveAddress($data['address'], $data['name']);
        if (is_object($saveAddressResponse) && property_exists($saveAddressResponse, 'success') && $saveAddressResponse->success) {
            $response = ['success' => true, 'message' => 'New address has been successfully added.'];
            $addresses = (new APIRequestsController())->getAddresses();
            if(!empty($addresses) && is_object($addresses) && property_exists($addresses, 'success') && $addresses->success) {
                $response['addresses'] = $addresses->data;
            }

            return response()->json($response);
        } else if (is_object($saveAddressResponse) && property_exists($saveAddressResponse, 'success') && !$saveAddressResponse->success) {
            return response()->json(['error' => true, 'message' => $saveAddressResponse->errors->generic]);
        } else {
            return response()->json(['error' => true, 'message' => 'Something went wrong, please try again later or email us at <a href=\'mailto:admin@dentacoin.com\'>admin@dentacoin.com</a>.']);
        }
    }

    protected function deleteAddress(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ], [
            'id.required' => 'Name is required.'
        ]);
        $data = $this->clearPostData($request->input());

        $deleteAddressResponse = (new APIRequestsController())->deleteAddress($data['id']);
        if (is_object($deleteAddressResponse) && property_exists($deleteAddressResponse, 'success') && $deleteAddressResponse->success) {
            return response()->json(['success' => true, 'message' => 'Address have been deleted successfully.']);
        } else if (is_object($deleteAddressResponse) && property_exists($deleteAddressResponse, 'success') && !$deleteAddressResponse->success) {
            return response()->json(['error' => true, 'message' => $deleteAddressResponse->errors->generic]);
        } else {
            return response()->json(['error' => true, 'message' => 'Something went wrong, please try again later or email us at <a href=\'mailto:admin@dentacoin.com\'>admin@dentacoin.com</a>.']);
        }
    }

    protected function getScanningData(Request $request) {
        $this->validate($request, [
            'slug' => 'required'
        ], [
            'slug.required' => 'Slug is required.'
        ]);

        $contract = TemporallyContract::where(array('slug' => $request->input('slug')))->get()->first();

        if(!empty($contract)) {
            return response()->json([
                'success' => true,
                'data' => array(
                    'patient' => $contract->patient_address,
                    'dentist' => $contract->dentist_address,
                    'usd' => $contract->monthly_premium,
                    'next_transfer' => strtotime($contract->contract_active_at),
                    'ipfs_hash' => $contract->document_hash
                )
            ]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    protected function markContractAsProcessing(Request $request) {
        $this->validate($request, [
            'slug' => 'required',
            'hash' => 'required',
            'to_status' => 'required',
            'transactionHash' => 'required',
        ], [
            'slug.required' => 'Slug is required.',
            'hash.required' => 'Hash is required.',
            'to_status.required' => 'Status is required.',
            'transactionHash.required' => 'Transaction is required.',
        ]);

        $patient_address = trim($request->input('patient_address'));
        $dentist_address = trim($request->input('dentist_address'));

        if (!empty($patient_address) && !empty($dentist_address)) {
            $slug = trim($request->input('slug'));
            if (hash('sha256', getenv('SECRET_PASSWORD').json_encode(array('slug' => $slug, 'patient_address' => $patient_address, 'dentist_address' => $dentist_address, 'to_status' => $request->input('to_status'), 'transactionHash' => $request->input('transactionHash')))) != $request->input('hash')) {
                return response()->json(['error' => true, 'message' => 'False hash.']);
            }

            $contract = TemporallyContract::where(array('slug' => $slug, 'patient_address' => $patient_address, 'dentist_address' => $dentist_address))->get()->first();
            if(!empty($contract)) {
                $contract->is_processing = true;
                $contract->save();

                $transaction = new contractTransactionHash();
                $wallet_signed = $request->input('wallet_signed');
                if (!empty($wallet_signed)) {
                    $transaction->wallet_signed = true;
                } else {
                    // setting this to true, because we don't need to camp for this flag change
                    $transaction->notified_front_end = true;
                }
                $transaction->transactionHash = trim($request->input('transactionHash'));
                $transaction->contract_slug = $slug;
                $transaction->to_status = trim($request->input('to_status'));

                $contractData = $request->input('contractData');
                if (!empty($contractData)) {
                    $transaction->data = $contractData;
                }

                $transaction->save();

                return response()->json(['success' => true]);
            } else {
                return response()->json(['error' => true]);
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Missing parameters.']);
        }
    }

    protected function requestContractStatusChange(Request $request) {
        $this->validate($request, [
            'slug' => 'required',
            'transactionHash' => 'required',
            'to_status' => 'required'
        ], [
            'slug.required' => 'Slug is required.',
            'transactionHash.required' => 'Transaction hash is required.',
            'to_status.required' => 'Status is required.'
        ]);

        $contract = TemporallyContract::where(array('slug' => $request->input('slug')))->get()->first();
        if(!empty($contract)) {
            //$approveContractStatusChange = (new APIRequestsController())->approveContractStatusChange($contract->patient_address, $contract->dentist_address, $request->input('to_status'));
            //if(is_object($approveContractStatusChange) && property_exists($approveContractStatusChange, 'success') && $approveContractStatusChange->success) {
            if($request->input('to_status') == 'cancelled') {
                if ($request->has('comments')) {
                    $hashArray = array(
                        'slug' => $request->input('slug'),
                        'transactionHash' => $request->input('transactionHash'),
                        'to_status' => $request->input('to_status'),
                        'type' => $request->input('type'),
                        'reason' => trim($request->input('reason')),
                        'comments' => trim($request->input('comments'))
                    );
                } else {
                    $hashArray = array(
                        'slug' => $request->input('slug'),
                        'transactionHash' => $request->input('transactionHash'),
                        'to_status' => $request->input('to_status'),
                        'type' => $request->input('type'),
                        'reason' => $request->input('reason')
                    );
                }
                $hash = hash('sha256', getenv('SECRET_PASSWORD').json_encode($hashArray));
            } else {
                $hash = hash('sha256', getenv('SECRET_PASSWORD').json_encode(array('slug' => $request->input('slug'), 'transactionHash' => $request->input('transactionHash'), 'to_status' => $request->input('to_status'))));
            }

            if ($hash != $request->input('updateContractStatusHash')) {
                return response()->json(['error' => true, 'message' => 'False hash.']);
            }

            if($request->input('to_status') == 'awaiting-approval' && $contract->status == 'awaiting-payment') {
                // creating contract
                (new PatientController())->changeToAwaitingApprovalStatus($contract);

                return response()->json(['success' => true]);
            } else if($request->input('to_status') == 'active' && $contract->status == 'awaiting-approval') {
                // approving contract
                $patient = (new APIRequestsController())->getUserData($contract->patient_id);
                (new DentistController())->changeToActiveStatus($contract, $patient);

                return response()->json(['success' => true]);
            } else if($request->input('to_status') == 'active-withdraw' && $contract->status == 'active') {
                // withdrawing from contract
                (new DentistController())->successfulBlockchainWithdraw($contract, $request->input('transactionHash'));

                return response()->json(['success' => true]);
            } else if($request->input('to_status') == 'cancelled') {
                // cancelling contract
                $type = trim($request->input('type'));
                $reason = trim($request->input('reason'));
                $comments = null;
                if (!empty($request->input('comments'))) {
                    $comments = trim($request->input('comments'));
                }

                if(!isset($type) || !isset($reason)) {
                    return response()->json(['error' => true]);
                }

                if($type == 'dentist') {
                    $initiator = (new APIRequestsController())->getUserData($contract->dentist_id);
                } else if($type == 'patient') {
                    if(!empty($contract->patient_id)) {
                        $initiator = (new APIRequestsController())->getUserData($contract->patient_id);
                    } else {
                        $initiator = (new APIRequestsController())->getUserByEmailAndType($contract->patient_email, 'patient');
                    }
                }

                $this->changeToCancelledStatus($contract, $type, $initiator, $reason, $comments);

                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['error' => true]);
        }
    }

    protected function authenticateUser(Request $request) {
        $this->validate($request, [
            'token' => 'required',
            'type' => 'required|in:patient,dentist',
            'id' => 'required'
        ], [
            'token.required' => 'Token is required.',
            'type.required' => 'Type is required.',
            'id.required' => 'ID is required.'
        ]);

        $checkToken = (new APIRequestsController())->checkUserIdAndToken($request->input('id'), $request->input('token'));
        if(is_object($checkToken) && property_exists($checkToken, 'success') && $checkToken->success) {
            $session_arr = [
                'token' => $request->input('token'),
                'id' => $request->input('id'),
                'type' => $request->input('type')
            ];

            session(['logged_user' => $session_arr]);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }
}
