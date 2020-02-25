<?php

namespace App\Http\Controllers;

use App\PublicKey;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    public static function instance() {
        return new UserController();
    }

    protected function getForgottenPasswordView() {
        if($this->checkSession()) {
            return redirect()->route('home');
        } else {
            return view('pages/forgotten-password');
        }
    }

    protected function getRecoverPassword() {
        if($this->checkSession()) {
            return redirect()->route('home');
        } else {
            if (!empty(Input::get('token'))) {
                return view('pages/recover-password');
            } else {
                return abort(404);
            }
        }
    }

    protected function forgottenPasswordSubmit(Request $request) {
        $this->validate($request, [
            'email' => 'required|max:100'
        ], [
            'email.required' => 'Email is required.',
        ]);

        $data = $this->clearPostData($request->input());

        //check email validation
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('forgotten-password')->with(['error' => 'Your form was not sent. Please try again with valid email.']);
        }

        $api_response = (new APIRequestsController())->generatePasswordRecoveryToken($data['email']);
        if (property_exists($api_response, 'success') && $api_response->success) {
            //$body = '<!DOCTYPE html><html><head></head><body><div style="font-size: 13px;">Seems like you forgot your password for Assurance Dentacoin. If this is true, click below to reset your password.<br><br><br><form target="_blank" method="POST" action="'.BASE_URL.'password-recover"><input type="hidden" name="slug" value="'.$api_response->data.'"/><input type="submit" value="PASSWORD RESET" style="font-size: 16px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;cursor: pointer;"/><input type="hidden" name="_token" value="'.csrf_token().'"></form></div></body></html>';
            $body = '<!DOCTYPE html><html><head></head><body><div style="font-size: 16px;">Seems like you forgot your password for Dentacoin. If this is true, click below to reset your password.<br><br><br><a href="'.BASE_URL.'password-recover?token='.urlencode($api_response->data).'" style="font-size: 20px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;cursor: pointer;">PASSWORD RESET</a></div></body></html>';

            Mail::send(array(), array(), function($message) use ($body, $data) {
                $message->to($data['email'])->subject('Dentacoin - Request for password change');
                $message->from(EMAIL_SENDER, 'Dentacoin - Assurance')->replyTo(EMAIL_SENDER, 'Dentacoin - Assurance');
                $message->setBody($body, 'text/html');
            });

            if(count(Mail::failures()) > 0) {
                return redirect()->route('forgotten-password')->with(['error' => 'Your form was not sent. Please try again later.']);
            } else {
                return redirect()->route('forgotten-password')->with(['success' => 'You have received an email with a password reset link.']);
            }
        } else {
            return redirect()->route('forgotten-password')->with(['error' => 'Your form was not sent. Please try again later.']);
        }
    }

    protected function changePasswordSubmit(Request $request) {
        $this->validate($request, [
            'token' => 'required',
            'password' => 'required|max:100',
        ], [
            'token.required' => 'Token is required.',
            'password.required' => 'Password is required.',
        ]);

        $data = $this->clearPostData($request->input());

        $post_fields_arr = [
            'token' => $data['token'],
            'password' => $data['password']
        ];

        $recover_method_response = (new APIRequestsController())->recoverPassword($post_fields_arr);
        if (property_exists($recover_method_response, 'success') && $recover_method_response->success) {
            return redirect()->route('home')->with(['success' => 'Your password has been changed successfully.']);
        } else {
            return redirect()->route('home')->with(['error' => 'Your password change failed, please try again later or request for new password recover.']);
        }
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
            return view('pages/logged-user/my-contracts', ['user_data' => $user_data, 'patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->sortByDesc('contract_active_at')]);
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
            return view('pages/logged-user/my-contracts-template', ['user_data' => $user_data, 'patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->sortByDesc('contract_active_at')]);
        }
    }

    protected function getAddressValidationOrRememberMe(Request $request) {
        $current_logged_user_dcn_address = (new APIRequestsController())->getUserData(session('logged_user')['id'])->dcn_address;
        //only dentists are able to cache their keys
        if(!empty($current_logged_user_dcn_address)) {
            $view = view('partials/address-validation-or-remember-me', ['current_logged_user_dcn_address' => $current_logged_user_dcn_address, 'cache' => $request->input('cache')]);
            $view = $view->render();
            return response()->json(['success' => $view]);
        } else {
            return response()->json(['error' => true]);
        }
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
        $current_user_data = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        if(empty($current_user_data->dcn_address)) {
            return response()->json(['error' => 'You cannot execute blockchain transactions without having your Wallet Address saved in your profile. Please save your Wallet Address in your profile and try again.']);
        }

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
                /*$dentist_data = $current_logged_user_data;
                $patient_data = (new APIRequestsController())->getUserData($contract->patient_id);*/
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

            $requestType = $request->input('type');
            if(!empty($requestType) && $requestType == 'cancel-with-blockchain-transaction') {
                $params['cancel_with_blockchain_transaction'] = true;
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

            return response()->json(['success' => $view, 'contract_data' => $contract_data, 'dcn_address' => $current_user_data->dcn_address]);
        } else {
            return response()->json(['error' => 'Transaction failed, please try again later.']);
        }
    }

    function checkEmail(Request $request) {
        $data = $this->clearPostData($request->input());
        $api_response = (new APIRequestsController())->checkIfFreeEmail($data['email']);
        if(property_exists($api_response, 'success') && $api_response->success) {
            return response()->json(['success' => true]);
        } else if(!$api_response->success) {
            return response()->json(['error' => true]);
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
                }else if(session('logged_user')['type'] == 'patient') {
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
            $current_logged_user = (new APIRequestsController())->getUserData(session('logged_user')['id']);
            if(session('logged_user')['id'] == $contract->patient_id || session('logged_user')['id'] == $contract->dentist_id || $current_logged_user->email == $contract->patient_email) {
                //CURL check if contract is still on the blockchain ???

                $cancellation_reason = array(
                    'reason' => $data['reason'],
                    'comments' => $data['comments']
                );

                $contract->status = $data['status'];
                $contract->cancelled_at = new \DateTime();
                $contract->cancellation_reason = serialize($cancellation_reason);
                $contract->save();

                $response['success'] = true;
                if($this->checkDentistSession()) {
                    $response['path'] = 'dentist';

                    if(!empty($contract->patient_id)) {
                        $patient = (new APIRequestsController())->getUserData($contract->patient_id);
                        $patient_name = $patient->name;
                        $patient_email = $patient->email;
                    } else {
                        $patient_name = $contract->patient_fname . ' ' . $contract->patient_lname;
                        $patient_email = $contract->patient_email;
                    }

                    $email_view = view('emails/dentist-cancel-contract', ['dentist_name' => $current_logged_user->name, 'patient_name' => $patient_name, 'reason' => $data['reason'], 'contract_slug' => $contract->slug]);
                    $body = $email_view->render();

                    Mail::send(array(), array(), function($message) use ($body, $patient_email, $current_logged_user) {
                        $message->to($patient_email)->subject($current_logged_user->name . ' Has Cancelled Your Contract');
                        $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                        $message->setBody($body, 'text/html');
                    });
                } else if($this->checkPatientSession()) {
                    $response['path'] = 'patient';

                    $dentist = (new APIRequestsController())->getUserData($contract->dentist_id);
                    $email_view = view('emails/patient-cancel-contract', ['dentist_name' => $dentist->name, 'patient_name' => $current_logged_user->name, 'reason' => $data['reason'], 'contract_slug' => $contract->slug]);
                    $body = $email_view->render();

                    Mail::send(array(), array(), function($message) use ($body, $dentist, $current_logged_user) {
                        $message->to($dentist->email)->subject($current_logged_user->name . ' Has Cancelled Their Contract');
                        $message->from(EMAIL_SENDER, 'Dentacoin Assurance Team')->replyTo(EMAIL_SENDER, 'Dentacoin Assurance Team');
                        $message->setBody($body, 'text/html');
                    });
                }
                echo json_encode($response);
                die();
            } else {
                $response['error'] = 'Cancellation failed, wrong contract.';
                echo json_encode($response);
                die();
            }
        } else {
            $response['error'] = 'Cancellation failed, wrong contract.';
            echo json_encode($response);
            die();
        }
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

    protected function getCurrentUserData() {
        return response()->json(['success' => (new APIRequestsController())->getUserData(session('logged_user')['id'])]);
    }

    public function getCountryNameById($id) {
        $countries = (new APIRequestsController())->getAllCountries();
        return $countries[$id - 1]->name;
    }

    protected function manageCustomCookie(Request $request) {
        if(!empty(Input::get('slug')) && !empty(Input::get('type')) && !empty(Input::get('token'))) {
            //logging
            $slug = $this->decrypt(Input::get('slug'));
            $type = $this->decrypt(Input::get('type'));
            $token = $this->decrypt(Input::get('token'));

            $user = (new APIRequestsController())->getUserData($slug);
            if($user) {
                $approved_statuses = array('approved', 'test');
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
            }
        } else {
            return abort(404);
        }
    }

    public function automaticContractCancel($contract, $onBlockchain = true) {
        $cancellation_reason = array(
            'reason' => 'Late payment from patient.'
        );
        $contract->status = 'cancelled';
        $contract->cancelled_at = new \DateTime();
        $contract->cancellation_reason = serialize($cancellation_reason);

        if($onBlockchain) {
            $gasPrice = (int)(new APIRequestsController())->getGasEstimationFromEthgasstation();
            $cancelContractParams = array(
                'patient_address' => $contract->patient_address,
                'dentist_address' => $contract->dentist_address,
                'gas_price' => $gasPrice
            );

            $check_if_legit_contract = (new APIRequestsController())->cancelIfLatePayment(hash('sha256', getenv('SECRET_PASSWORD').json_encode($cancelContractParams)), $contract->patient_address, $contract->dentist_address, $gasPrice);
            if(is_object($check_if_legit_contract) && property_exists($check_if_legit_contract, 'success') && $check_if_legit_contract->success) {
                //IF NORMAL PERIOD AND GRACE PERIOD PASSED CANCEL THIS CONTRACT
                $contract->save();

                // send cancel notification email to dentist and patient
            }
        } else {
            if(!empty($contract->contract_active_at)) {
                // awaiting-payment

                // if contract has not been funded for the withdraw period + the grace period => cancel the contract
                if(time() > strtotime($contract->contract_active_at. ' + '.(DAYS_CONTRACT_WITHDRAWAL_PERIOD + GRACE_PERIOD).' days')) {
                    $contract->save();
                }
            } else {
                // pending
                if(time() > strtotime($contract->created_at->toDateTimeString(). ' + '.DAYS_ACTIVE_CONTRACT_PROPOSAL.' days')) {
                    $contract->save();
                }
            }
        }

        return $contract;
    }
}
