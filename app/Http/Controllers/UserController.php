<?php

namespace App\Http\Controllers;

use App\PublicKey;
use App\TemporallyContract;
use Illuminate\Http\Request;

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
            return view('pages/logged-user/my-contracts', ['other_side_label' => 'Dentist', 'contracts' => TemporallyContract::where(array('patient_id' => session('logged_user')['id']))->orWhere(array('patient_email' => (new APIRequestsController())->getUserData(session('logged_user')['id'])->email))->get()->sortByDesc('created_at')]);
        } else if($this->checkDentistSession()) {
            return view('pages/logged-user/my-contracts', ['other_side_label' => 'Patient', 'contracts' => TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->sortByDesc('created_at')]);
        }
    }

    protected function getForgottenPasswordView() {
        return view('pages/forgotten-password');
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
        $response = array();

        try {
            $public_key = new PublicKey();
            $public_key->address = $data['address'];
            $public_key->public_key = $data['public_key'];
            $public_key->save();

            $response['success'] = true;
            echo json_encode($response);
            die();
        } catch(Exception $e) {
            $response['error'] = 'Verifying failed, please try again later.';
            echo json_encode($response);
            die();
        }
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

    public function readAndDecryptIPFSZip($ipfs_hash, $key, $dentist = null, $patient = null) {
        $folder_path = ZIP_EXTRACTS . $ipfs_hash . '-' . session('logged_user')['id'];
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
            file_put_contents($folder_path . DS . $ipfs_hash . '.zip', fopen('http://ipfs.io/ipfs/' . $ipfs_hash, 'r'));

            $zipper = new \Chumper\Zipper\Zipper;
            $zipper->make($folder_path . DS . $ipfs_hash . '.zip')->extractTo($folder_path);
            $zipper->close();

            if($dentist) {
                $file_name = 'dentist-pdf-file.pdf';
            } else if($patient) {
                $file_name = 'patient-pdf-file.pdf';
            }

            $parser = new \Smalot\PdfParser\Parser();
            $encrypted_pdf = $parser->parseFile($folder_path . DS . $file_name)->getText();

            $encrypted_html_by_patient = (new \App\Http\Controllers\APIRequestsController())->decryptFile($key, $encrypted_pdf);
            var_dump($encrypted_html_by_patient);
            die();
        }
        die('asd');
    }

    protected function forgottenPasswordSubmit(Request $request) {
        var_dump($request->input());
        die();
    }
}
