<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIRequestsController extends Controller {
    public function dentistLogin($data) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/login',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'platform' => 'assurance',
                'type' => 'dentist',
                'email' => $data['email'],
                'password' => $data['password']
            )
        ));

        $resp = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function dentistRegister($data, $files) {
        $post_fields_arr = array(
            'platform' => 'assurance',
            'name' => $data['dentist-or-practice-name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'password-repeat' => $data['repeat-password'],
            'country_code' => $data['country-code'],
            'address' => $data['address'],
            'avatar' => curl_file_create($files['image']->getPathName(), 'image/'.pathinfo($files['image']->getClientOriginalName(), PATHINFO_EXTENSION), $files['image']->getClientOriginalName()),
            'phone' => $data['phone'],
            'website' => $data['website'],
            'specialisations' => json_encode($data['specializations'])
        );

        switch($data['work-type']) {
            case 'independent-dental-practitioner':
                $post_fields_arr['type'] = 'dentist';
                break;
            case 'represent-dental-practice':
                $post_fields_arr['type'] = 'clinic';
                break;
            case 'an-associate-dentist':
                $post_fields_arr['type'] = 'dentist';

                if(!empty($data['clinic-id'])) {
                    $post_fields_arr['clinic_id'] = $data['clinic-id'];
                }
                break;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/register',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $post_fields_arr
        ));

        $resp = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function getAllCountries() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/countries/',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        if(!empty($resp))   {
            return $resp->data;
        }else {
            return false;
        }
    }

    public function getAllEnums() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/enums/',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        if(!empty($resp))   {
            return $resp->data;
        }else {
            return false;
        }
    }

    public function getAllClinicsByName($name = null) {
        $post_fields_arr = array(
            'type' => 'clinic',
            'items_per_page' => 2000
        );

        if(!empty($name)) {
            $post_fields_arr['name'] = $name;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/users/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $post_fields_arr
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp->data;
        }else {

            return response()->json(['error' => 'API not working at this moment. Try again later.']);
        }
    }

    public function getPatientsByEmail($email) {
        $post_fields_arr = array(
            'type' => 'dentist',
            'email' => $email,
            'is_approved' => true
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/users/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $post_fields_arr
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp->data;
        }else {

            return response()->json(['error' => 'API not working at this moment. Try again later.']);
        }
    }

    public function getUserData($id) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/user/'.$id,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp->data;
        }else {
            return false;
        }
    }

    public function checkIfUserExist($email) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/check-email/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'email' => $email
            )
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function generatePasswordRecoveryToken($email) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/recoverToken/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'email' => $this->encrypt($email)
            )
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function recoverPassword($data) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/recoverPassword/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'recoverToken' => $data['token'],
                'password' => $this->encrypt($data['password']),
            )
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function updateUserData($data) {
        if(isset($data['specialisations'])) {
            $data['specialisations'] = json_encode($data['specialisations']);
        }

        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/user/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function deleteProfile() {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/user/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'self_deleted' => true
            ),
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    //this method is not from the CoreDB, but from the IPFS NODEJS API on the website server
    public function uploadFileToIPFS($file_path) {
        $curl = curl_init();
        //$json = '{"filename":"/../assurance.dentacoin.com/public/assets/lorem-ipsum.pdf"}';
        $json = '{"filename":"'.$file_path.'"}';
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://dev-test.dentacoin.com/upload-file-to-ipfs',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $json
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(    //<--- Added this code block
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json))
        );

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    //this method is not from the CoreDB, but from the IPFS NODEJS API on the website server
    public function encryptFile($key, $html) {
        var_dump(str_replace('"',"\'", $html));
        die();
        $curl = curl_init();
        $json = '{"public_key":"'.$key.'", "html":"'.str_replace('"','\"', htmlentities('<label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label><label class="inline-block">Professional / Company Registration Number:</label>')).'"}';

        var_dump(json_decode($json));
        die();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://dev-test.dentacoin.com/encrypt',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $json
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(    //<--- Added this code block
            'Content-Type: application/json',
            'Content-Length: ' . mb_strlen($json))
        );

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        var_dump($resp);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    //this method is not from the CoreDB, but from the IPFS NODEJS API on the website server
    public function registerDCNReward($data) {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/rewards',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function getDCNBalance() {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/balance',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function withdraw($amount) {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        var_dump($amount);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://api.dentacoin.com/api/transcations/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'amount' => $amount
            ),
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = curl_exec($curl);
        curl_close($curl);

        var_dump($resp);
        die();

        if(!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }
}
