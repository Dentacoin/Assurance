<?php

namespace App\Http\Controllers;

use App\PublicKey;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

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

    protected function getMyContractsView()     {
        if($this->checkPatientSession()) {
            return view('pages/logged-user/my-contracts', ['patient_or_not' => false, 'contracts' => TemporallyContract::where(array('patient_id' => session('logged_user')['id']))->orWhere(array('patient_email' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->email))->get()->sortByDesc('created_at')]);
        } else if($this->checkDentistSession()) {
            return view('pages/logged-user/my-contracts', ['patient_or_not' => true, 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->sortByDesc('created_at')]);
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

        return view('pages/logged-user/my-profile', ['currency_arr' => $currency_arr, 'dcn_amount' => 123456]);
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
        $this->validate($request, [
            'full-name' => 'required|max:250',
            'email' => 'required|max:100',
            'country' => 'required',
            'dcn_address' => 'required',
        ], [
            'full-name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'country.required' => 'Country is required.',
            'dcn_address.required' => 'Wallet Address is required.',
        ]);

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
            'email' => $data['email'],
            'country_code' => $data['country'],
            'dcn_address' => $data['dcn_address'],
        );

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
        $contract = TemporallyContract::where(array('slug' => $data['contract'], 'patient_email' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->email))->get()->first();
        if($contract) {
            $contract->status = $data['status'];
            $contract->cancelled_at = new \DateTime();
            $contract->save();
            $response['success'] = true;
        } else {
            $response['error'] = 'Cancellation failed, wrong contract.';
        }
        echo json_encode($response);
        die();
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
        var_dump($request->input());
        die();
    }
}
