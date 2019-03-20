<?php

namespace App\Http\Controllers;

use App\PublicKey;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Validator;

class UserController extends Controller {
    public static function instance() {
        return new UserController();
    }

    protected function getEditAccountView()   {
        return view('pages/logged-user/edit-account', ['countries' => (new APIRequestsController())->getAllCountries(), 'user_data' => (new APIRequestsController())->getUserData(session('logged_user')['id'])]);
    }

    protected function getManagePrivacyView()   {
        return view('pages/logged-user/manage-privacy');
    }

    protected function getRecoverPassword(Request $request) {
        $this->validate($request, [
            'slug' => 'required'
        ], [
            'slug.required' => 'Slug is required.'
        ]);
        return view('pages/recover-password', ['slug' => $request->input('slug')]);
    }

    protected function getMyContractsView()     {
        if($this->checkPatientSession()) {
            return view('pages/logged-user/my-contracts', ['patient_or_not' => false, 'contracts' => TemporallyContract::where(array('patient_id' => session('logged_user')['id']))->orWhere(array('patient_email' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->email))->get()->sortByDesc('created_at')]);
        } else if($this->checkDentistSession()) {
            if(!empty(Input::get('status'))) {
                //if get parameter is passed query by status
                return view('pages/logged-user/my-contracts', ['patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id'], 'status' => Input::get('status')))->get()->sortByDesc('created_at')]);
            }
            return view('pages/logged-user/my-contracts', ['patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->sortByDesc('contract_active_at')]);
        }
    }

    protected function getForgottenPasswordView() {
        return view('pages/forgotten-password');
    }

    protected function getAddressValidationOrRememberMe(Request $request) {
        $current_logged_user_dcn_address = (new APIRequestsController())->getUserData(session('logged_user')['id'])->dcn_address;
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
                if(!empty($contract->patient_id)) {
                    $receiver_name = (new APIRequestsController())->getUserData($contract->patient_id)->name;
                } else {
                    $receiver_name = $contract->patient_fname . ' ' . $contract->patient_lname;
                }
            } else if($this->checkPatientSession()) {
                $receiver_name = (new APIRequestsController())->getUserData($contract->dentist_id)->name;
            }
            $view = view('partials/popup-cancel-contract', ['receiver_name' => $receiver_name]);
            $view = $view->render();
            return response()->json(['success' => $view]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    protected function getRecipePopup(Request $request) {
        $current_user_data = (new APIRequestsController())->getUserData(session('logged_user')['id']);
        if(empty($current_user_data->dcn_address)) {
            return response()->json(['error' => 'You cannot execute blockchain transactions without having your Wallet Address saved in your profile. Please save your Wallet Address in your profile and try again.']);
        }

        $contract = TemporallyContract::where(array('slug' => $request->input('contract')))->get()->first();
        if($contract) {
            $current_logged_user_data = (new APIRequestsController())->getUserData(session('logged_user')['id']);
            if($this->checkDentistSession()) {
                $dentist_data = $current_logged_user_data;
                $patient_data = (new APIRequestsController())->getUserData($contract->patient_id);
            } else if($this->checkPatientSession()) {
                $dentist_data = (new APIRequestsController())->getUserData($contract->dentist_id);
                $patient_data = $current_logged_user_data;
            }

            $view = view('partials/transaction-recipe-popup', ['to' => $request->input('to'), 'current_logged_user' => $current_logged_user_data, 'cached_key' => $request->input('cached_key'), 'show_dcn_bar' => $request->input('show_dcn_bar'), 'recipe_title' => $request->input('recipe_title'), 'recipe_subtitle' => $request->input('recipe_subtitle'), 'recipe_checkbox_text' => $request->input('recipe_checkbox_text')]);
            $view = $view->render();
            $contract_data = array(
                'patient' => $patient_data->dcn_address,
                'dentist' => $dentist_data->dcn_address,
                'value_usd' => $contract->monthly_premium,
                'date_start_contract' => strtotime($contract->contract_active_at),
                'contract_ipfs_hash' => $contract->document_hash
            );

            return response()->json(['success' => $view, 'contract_data' => $contract_data, 'dcn_address' => $current_user_data->dcn_address]);
        } else {
            return response()->json(['error' => 'Transaction failed, please try again later.']);
        }
    }

    protected function getMyProfileView()   {
        $currency_arr = array();
        foreach(Controller::currencies as $currency) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.coinmarketcap.com/v1/ticker/dentacoin/?convert=' . $currency,
                CURLOPT_SSL_VERIFYPEER => 0
            ));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $resp = json_decode(curl_exec($curl));
            curl_close($curl);
            $currency_arr[strtolower($currency)] = (array)$resp[0];
        }

        $view_params = array('currency_arr' => $currency_arr);

        $dcn_balance_api_method_response = (new APIRequestsController())->getDCNBalance();
        if($dcn_balance_api_method_response && $dcn_balance_api_method_response->success) {
            $view_params['dcn_amount'] = $dcn_balance_api_method_response->data;
        }

        return view('pages/logged-user/my-profile', $view_params);
    }

    protected function getLoginSigninHtml(Request $request) {
        //passing the countries
        $countries = (new APIRequestsController())->getAllCountries();
        $clinics = (new APIRequestsController())->getAllClinicsByName();
        $params = ['countries' => $countries, 'clinics' => $clinics, /*'current_user_country_code' => mb_strtolower(trim(file_get_contents("http://ipinfo.io/" . $_SERVER['REMOTE_ADDR'] .  "/country"))),*/ 'current_user_country_code' => 'bg'];
        if(!empty($request->input('route')) && !empty($request->input('slug'))) {
            $params['route'] = $request->input('route');
            $params['slug'] = $request->input('slug');
        }

        if(!empty($request->input('side'))) {
            $params['side'] = $request->input('side');
        }
        $view = view('partials/login-signin', $params);
        $view = $view->render();
        return response()->json(['success' => $view]);
    }

    function checkEmail(Request $request) {
        $data = $this->clearPostData($request->input());
        $api_response = (new APIRequestsController())->checkIfFreeEmail($data['email']);
        if($api_response->success) {
            return response()->json(['success' => true]);
        } else if(!$api_response->success) {
            return response()->json(['error' => true]);
        }
    }

    function checkCaptcha(Request $request) {
        $temp_save_captcha_session = session('captcha');
        //saving the session again, because theres bug in the captcha library

        if (captcha_check($request->input('captcha'))) {
            session(['captcha' => $temp_save_captcha_session]);;
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
        if($request->session()->has('logged_user'))    {
            if(session('logged_user')['type'] == 'dentist') {
                $route = 'home';
            }else if(session('logged_user')['type'] == 'patient') {
                $route = 'patient-access';
            }
            $request->session()->forget('logged_user');
        }
        return redirect()->route($route);
    }

    protected function updateAccount(Request $request) {
        $arr_with_required_data = array(
            'full-name' => 'required|max:250',
            'email' => 'required|max:100'
        );

        $arr_with_required_data_responces = array(
            'full-name.required' => 'Name is required.',
            'email.required' => 'Email address is required.'
        );

        //if logged user is dentist require the specialisations data
        if($this->checkDentistSession()) {
            $arr_with_required_data['specialisations'] = 'required';
            $arr_with_required_data['country'] = 'required';
            $arr_with_required_data['address'] = 'required';
            $arr_with_required_data_responces['specialisations.required'] = 'Specialisations are required.';
            $arr_with_required_data_responces['country.required'] = 'Country is required.';
            $arr_with_required_data_responces['address.required'] = 'Postal Address is required.';
        }

        $this->validate($request, $arr_with_required_data, $arr_with_required_data_responces);

        $data = $this->clearPostData($request->input());
        $files = $request->file();

        //check email validation
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))   {
            return redirect()->route('edit-account')->with(['error' => 'Your form was not sent. Please try again with valid email.']);
        }

        if(!empty($files)) {
            //404 if they're trying to send more than 2 files
            if(sizeof($files) > 2) {
                return abort(404);
            } else {
                $allowed = array('png', 'jpg', 'jpeg', 'svg', 'bmp', 'PNG', 'JPG', 'JPEG', 'SVG', 'BMP');
                foreach($files as $file)  {
                    //checking the file size
                    if($file->getSize() > MAX_UPL_SIZE) {
                        return redirect()->route('edit-account', ['slug' => $request->input('post-slug')])->with(['error' => 'Your form was not sent. Files can be only with with maximum size of '.number_format(MAX_UPL_SIZE / 1048576).'MB. Please try again.']);
                    }
                    //checking file format
                    if(!in_array(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION), $allowed)) {
                        return redirect()->route('edit-account')->with(['error' => 'Your form was not sent. Files can be only with .png, .jpg, .jpeg, .svg, .bmp formats. Please try again.']);
                    }
                    //checking if error in file
                    if($file->getError()) {
                        return redirect()->route('edit-account')->with(['error' => 'Your form was not sent. There is error with one or more of the files, please try with other files. Please try again.']);
                    }
                }
            }
        }

        $post_fields_arr = array(
            'name' => $data['full-name'],
            'email' => $data['email']
        );

        if(isset($data['country_code']) && !empty($data['country_code'])) {
            $post_fields_arr['country_code'] = $data['country_code'];
        }

        if(isset($data['address']) && !empty($data['address'])) {
            $post_fields_arr['address'] = $data['address'];
        }

        if(isset($data['dcn_address']) && !empty($data['dcn_address'])) {
            $post_fields_arr['dcn_address'] = $data['dcn_address'];
        }

        if($this->checkDentistSession()) {
            $post_fields_arr['specialisations'] = $data['specialisations'];
        }

        //if the logged user is dentist he must provide website and phone
        if(isset($data['website']) || isset($data['phone'])) {
            if(isset($data['website'])) {
                if(!empty($data['website'])) {
                    $post_fields_arr['website'] = $data['website'];
                }else {
                    return redirect()->route('edit-account')->with(['error' => 'Website is required']);
                }
            }
            if(isset($data['phone'])) {
                if(!empty($data['phone'])) {
                    $post_fields_arr['phone'] = $data['phone'];
                }else {
                    return redirect()->route('edit-account')->with(['error' => 'Phone is required']);
                }
            }
        }

        //if user selected new avatar submit it to the api
        if(!empty($files['image'])) {
            $post_fields_arr['avatar'] = curl_file_create($files['image']->getPathName(), 'image/'.pathinfo($files['image']->getClientOriginalName(), PATHINFO_EXTENSION), $files['image']->getClientOriginalName());
        }

        //handle the API response
        $api_response = (new APIRequestsController())->updateUserData($post_fields_arr);
        if($api_response) {
            return redirect()->route('edit-account')->with(['success' => 'Your data was updated successfully.']);
        } else {
            return redirect()->route('edit-account')->with(['errors_response' => $api_response['errors']]);
        }
    }

    protected function addDcnAddress(Request $request) {
        $data = $this->clearPostData($request->input());
        $post_fields_arr = array(
            'dcn_address' => $data['address']
        );

        //handle the API response
        $api_response = (new APIRequestsController())->updateUserData($post_fields_arr);
        if($api_response) {
            return redirect()->route('my-profile')->with(['success' => 'Your Wallet Address was saved successfully.']);
        } else {
            return redirect()->route('my-profile')->with(['errors_response' => $api_response['errors']]);
        }
    }

    protected function updateContractStatus(Request $request) {
        $data = $this->clearPostData($request->input());
        $response = array();
        $contract = TemporallyContract::where(array('slug' => $data['contract']))->get()->first();
        if($contract) {
            if(session('logged_user')['id'] == $contract->patient_id || session('logged_user')['id'] == $contract->dentist_id || (new APIRequestsController())->getUserData(session('logged_user')['id'])->email == $contract->patient_email) {
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
                } else if($this->checkPatientSession()) {
                    $response['path'] = 'patient';
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
        if($api_response->success) {
            $body = '<!DOCTYPE html><html><head></head><body><div style="font-size: 16px;">Seems like you forgot your password for Assurance Dentacoin. If this is true, click below to reset your password.<br><br><br><form target="_blank" method="POST" action="'.BASE_URL.'password-recover"><input type="hidden" name="slug" value="'.$api_response->data.'"/><input type="submit" value="PASSWORD RESET" style="font-size: 20px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;cursor: pointer;"/><input type="hidden" name="_token" value="'.csrf_token().'"></form></div></body></html>';

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
        if($recover_method_response->success) {
            return redirect()->route('home')->with(['success' => 'Your password has been changed successfully.']);
        } else {
            return redirect()->route('home')->with(['error' => 'Your password change failed, please try again later.']);
        }
    }

    protected function filterMyContracts(Request $request) {
        $view_params = array();
        if(!empty($request->input('filter_arr'))) {
            //FILTERING
            $view_params['patient_or_not'] = true;
            if($this->checkPatientSession()) {
                $view_params['contracts'] = TemporallyContract::where(array('patient_id' => session('logged_user')['id']))->whereIn('status', $request->input('filter_arr'))->orWhere(array('patient_email' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->email))->orderBy('contract_active_at', 'desc')->get()->all();
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

    protected function deleteMyProfile(Request $request) {
        $api_response = (new APIRequestsController())->deleteProfile();
        if($api_response->success) {
            $this->userLogout($request);
            return redirect()->route('home')->with(['success' => 'Your profile has been deleted successfullym.']);
        } else {
            return redirect()->route('manage-privacy')->with(['error' => 'Your profile deletion failed. Please try again later.']);
        }
    }

    protected function getCurrentUserData() {
        return response()->json(['success' => (new APIRequestsController())->getUserData(session('logged_user')['id'])]);
    }

    protected function validateCivicKyc(Request $request) {
        $this->validate($request, [
            'token' => 'required'
        ], [
            'token.required' => 'Token is required.'
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/validateCivicToken',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'token' => $request->input('token')
            )
        ));

        $civic_validation_response = json_decode(curl_exec($curl));
        curl_close($curl);

        if($civic_validation_response->success) {
            $update_user_data_response = (new APIRequestsController())->updateUserData(array('civic_kyc' => 1));
            if(!$update_user_data_response) {
                return response()->json(['error' => 'Civic authentication failed.']);
            }
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Civic authentication failed.']);
        }
    }

    protected function withdraw(Request $request) {
        $this->validate($request, [
            'amount' => 'required',
            'address' => 'required'
        ], [
            'amount.required' => 'Amount is required.',
            'address.required' => 'Wallet Address is required.'
        ]);

        $data = $this->clearPostData($request->input());

        $dcn_balance_api_method_response = (new APIRequestsController())->getDCNBalance();
        $failed_withdraw_error_msg = 'Withdraw failed, please try again later or contact <a href="mailto:assurance@dentacoin.com">assurance@dentacoin.com</a>.';
        if($dcn_balance_api_method_response->success) {
            //checking if the withdrawing amount is more than the balance
            if((int)$data['amount'] > $dcn_balance_api_method_response->data) {
                return redirect()->route('my-profile')->with(['error' => $failed_withdraw_error_msg]);
            } else {
                $current_user_data = (new APIRequestsController())->getUserData(session('logged_user')['id']);
                if($current_user_data->dcn_address != $data['address']) {
                    $api_response = (new APIRequestsController())->updateUserData(array('dcn_address' => $data['address']));
                    if(!$api_response) {
                        return redirect()->route('my-profile')->with(['error' => $failed_withdraw_error_msg]);
                    } else {
                        $withdraw_response = (new APIRequestsController())->withdraw($data['amount']);
                        if($withdraw_response && $withdraw_response->success && $withdraw_response->data->transaction->success) {
                            return redirect()->route('my-profile')->with(['success' => "Your transaction was confirmed. Check here  <a href='https://etherscan.io/tx/".$withdraw_response->data->transaction->message."' class='etherscan-link' target='_blank'>Etherscan</a>."]);
                        } else {
                            return redirect()->route('my-profile')->with(['error' => $failed_withdraw_error_msg]);
                        }
                    }
                } else {
                    $withdraw_response = (new APIRequestsController())->withdraw($data['amount']);
                    if($withdraw_response && $withdraw_response->success && $withdraw_response->data->transaction->success) {
                        return redirect()->route('my-profile')->with(['success' => "Your transaction was confirmed. Check here  <a href='https://etherscan.io/tx/".$withdraw_response->data->transaction->message."' class='etherscan-link' target='_blank'>Etherscan</a>."]);
                    } else {
                        return redirect()->route('my-profile')->with(['error' => $failed_withdraw_error_msg]);
                    }
                }
            }
        } else {
            return redirect()->route('my-profile')->with(['error' => $failed_withdraw_error_msg]);
        }
    }

    public function getCountryNameById($id) {
        $countries = (new APIRequestsController())->getAllCountries();
        return $countries[$id - 1]->name;
    }

    protected function downloadGDPRData() {
        $api_response = (new APIRequestsController())->getGDPRDownloadLink();
        if($api_response->success) {
            return response()->json(['success' => $api_response->data]);
        } else {
            return response()->json(['error' => 'Downloading your personal data is not possible at the moment, please try again later.']);
        }
    }

    protected function getUserDataForNodeJSApi(Request $request) {
        $contract = TemporallyContract::where(array('document_hash' => $request->input('hash')))->get()->first();
        $dentist = (new APIRequestsController())->getUserData($contract->dentist_id);
        $patient = (new APIRequestsController())->getUserData($contract->patient_id);
        return response()->json(['success' => true, 'dentist_name' => $dentist->name, 'patient_name' => $patient->name, 'patient_email' => $patient->email, 'slug' => $contract->slug]);
    }
}
