<?php

namespace App\Http\Controllers;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class APIRequestsController extends Controller {
    public function dentistLogin($data, $dontCountLogin = false) {
        $postData = array(
            'platform' => 'assurance',
            'type' => 'dentist',
            'email' => $data['email'],
            'password' => $data['password'],
            'client_ip' => $this->getClientIp()
        );

        if ($dontCountLogin) {
            $postData['dont_count_login'] = true;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/login',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $postData
        ));

        $resp = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (!empty($resp)) {
            return $resp;
        } else {
            return false;
        }
    }

    public function dentistRegister($data, $files) {
        $post_fields_arr = array(
            'platform' => 'assurance',
            'title' => trim($data['dentist-title']),
            'name' => trim($data['latin-name']),
            'email' => trim($data['email']),
            'password' => trim($data['password']),
            'password-repeat' => trim($data['repeat-password']),
            'country_code' => trim($data['country-code']),
            'address' => trim($data['address']),
            'avatar' => curl_file_create($data['image-path'], 'image/png', $data['image-name']),
            /*'avatar' => curl_file_create($files['image']->getPathName(), 'image/'.pathinfo($files['image']->getClientOriginalName(), PATHINFO_EXTENSION), $files['image']->getClientOriginalName()),*/
            'phone' => trim($data['phone']),
            'website' => trim($data['website']),
            'specialisations' => json_encode($data['specializations']),
            'client_ip' => $this->getClientIp()
        );

        if (!empty($data['alternative-name'])) {
            $post_fields_arr['name_alternative'] = trim($data['alternative-name']);
        }

        if (!empty($data['inviter'])) {
            $post_fields_arr['invited_by'] = trim($data['inviter']);
        }

        switch($data['user-type']) {
            case 'dentist':
                $post_fields_arr['type'] = 'dentist';
                break;
            case 'clinic':
                $post_fields_arr['type'] = 'clinic';
                break;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/register',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $post_fields_arr
        ));

        $resp = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function getAllCountries() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/countries/',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        if (!empty($resp))   {
            return $resp->data;
        }else {
            return false;
        }
    }

    public function getAllEnums() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/enums/',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        if (!empty($resp))   {
            return $resp->data;
        }else {
            return false;
        }
    }

    public function getAllClinicsByName($name = null) {
        $post_fields_arr = array(
            'type' => 'all-dentists',
            'items_per_page' => 2000,
            'status' => 'approved'
        );

        if (!empty($name)) {
            $post_fields_arr['name'] = $name;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/users/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'users_details' => (new \App\Http\Controllers\Controller())->encrypt(json_encode($post_fields_arr) , getenv('API_ENCRYPTION_METHOD'), getenv('API_ENCRYPTION_KEY'))
            )
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp->data;
        }else {

            return response()->json(['error' => 'API not working at this moment. Try again later.']);
        }
    }

    public function getUserByEmailAndType($email, $type) {
        $post_fields_arr = array(
            'type' => $type,
            'email' => $email
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/users/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'users_details' => (new \App\Http\Controllers\Controller())->encrypt(json_encode($post_fields_arr) , getenv('API_ENCRYPTION_METHOD'), getenv('API_ENCRYPTION_KEY'))
            )
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp->data;
        } else {
            return response()->json(['error' => 'API not working at this moment. Try again later.']);
        }
    }

    public function getUserData($id, $fullResponse = false) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/user/?id='.urlencode($this->encrypt($id, getenv('API_ENCRYPTION_METHOD'), getenv('API_ENCRYPTION_KEY'))),
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp) && property_exists($resp, 'data') && !empty($resp->data))   {
            if ($fullResponse) {
                return $resp;
            } else {
                return $resp->data;
            }
        }else {
            return false;
        }
    }

    public function getUserDataByEmail($email) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/user/?email='.urlencode($this->encrypt($email, API_ENCRYPTION_METHOD, API_ENCRYPTION_KEY)),
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        } else {
            return false;
        }
    }

    public function checkIfFreeEmail($email) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/check-email',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'email' => $email
            )
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function updateUserData($data) {
        if (isset($data['specialisations'])) {
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
            CURLOPT_URL => getenv('API_DOMAIN').'/api/user/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (!empty($resp))   {
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
            CURLOPT_URL => 'https://methods.dentacoin.com/upload-file-to-ipfs',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $json
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(    //<--- Added this code block
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($json))
        );

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        return $resp;
    }

    public function getGasEstimationFromEthgasstation()  {
        //API connection
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://ethgasstation.info/json/ethgasAPI.json',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        if (!empty($resp))   {
            return $resp->safeLow;
        }
    }

    //this method is not from the CoreDB, but from the IPFS NODEJS API on the website server
    public function sendEthAmount($hash, $type, $patient_address, $dentist_address, $gasPrice, $value_usd = null, $monthly_premium_in_dcn = null, $time = null, $contract_ipfs_hash = null) {
        $curl = curl_init();

        if ($type == 'dentist-approval') {
            $json = '{"hash":"'.$hash.'", "type":"'.$type.'", "patient_address":"'.$patient_address.'", "dentist_address":"'.$dentist_address.'", "gas_price":"'.$gasPrice.'", "network":"'.getenv('NETWORK').'"}';
        } else if ($type == 'patient-approval-and-contract-creation') {
            $json = '{"hash":"'.$hash.'", "type":"'.$type.'", "patient_address":"'.$patient_address.'", "dentist_address":"'.$dentist_address.'", "value_usd":"'.$value_usd.'", "monthly_premium_in_dcn":"'.$monthly_premium_in_dcn.'", "time":"'.$time.'", "contract_ipfs_hash":"'.$contract_ipfs_hash.'", "gas_price":"'.$gasPrice.'", "network":"'.getenv('NETWORK').'"}';
        }

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://assurance.dentacoin.com/send-eth',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $json
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(    //<--- Added this code block
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($json))
        );

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        return $resp;
    }

    //this method is not from the CoreDB, but from the IPFS NODEJS API on the website server
    public function encryptFile($key, $html) {
        $curl = curl_init();

        $array = array(
            'public_key' => $key,
            'post_html' => $html
        );
        $json = json_encode($array);

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://methods.dentacoin.com/encrypt',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $json
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(    //<--- Added this code block
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($json))
        );

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        return $resp;
    }

    public function registerDCNReward($data) {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/rewards',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
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
            CURLOPT_URL => getenv('API_DOMAIN').'/api/balance',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function getDCNTransactions() {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/transcations/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    //get testimonials stored in dentacoin.com admin
    public function getTestimonials() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://dentacoin.com/info/testimonials',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    //this method is not from the CoreDB
    public function cancelIfLatePayment($hash, $patient_address, $dentist_address, $gasPrice) {
        $curl = curl_init();

        $json = '{"hash":"'.$hash.'", "patient_address":"'.$patient_address.'", "dentist_address":"'.$dentist_address.'", "gas_price":"'.$gasPrice.'", "network":"'.getenv('NETWORK').'"}';

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://assurance.dentacoin.com/late-payment-cancel-contract',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $json
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(    //<--- Added this code block
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($json))
        );

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        return $resp;
    }

    //this method is not from the CoreDB
    public function approveContractStatusChange($patient_address, $dentist_address, $to_status) {
        $curl = curl_init();

        $json = '{"patient_address":"'.$patient_address.'", "dentist_address":"'.$dentist_address.'", "to_status":"'.$to_status.'"}';

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => 'https://assurance.dentacoin.com/approve-contract-status-change',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $json
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(    //<--- Added this code block
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($json))
        );

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        return $resp;
    }

    public function updateAnonymousUserData($data) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/user-anonymous/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $data
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    /*public function getCurrentDcnRateByCoingecko()  {
        //API connection
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
            if (!empty($resp->market_data->current_price))  {
                return array(
                    'USD' => $resp->market_data->current_price->usd,
                    'EUR' => $resp->market_data->current_price->eur,
                    'GBP' => $resp->market_data->current_price->gbp,
                    'RUB' => $resp->market_data->current_price->rub,
                    'INR' => $resp->market_data->current_price->inr,
                    'CNY' => $resp->market_data->current_price->cny,
                    'JPY' => $resp->market_data->current_price->jpy
                );
            } else {
                return 0;
            }
        }
    }*/

    public function getDentacoinDataByExternalProvider()  {
        // check if external provider price reading is allowed
        if  (!empty(getenv('EXTERNAL_PROVIDER_PRICE_READING')) && !empty(getenv('EXTERNAL_PROVIDER_PRICE_READING_TYPE')) && filter_var(getenv('EXTERNAL_PROVIDER_PRICE_READING'), FILTER_VALIDATE_BOOLEAN)) {
            if (getenv('EXTERNAL_PROVIDER_PRICE_READING_TYPE') == 'indacoin') {
                $currencies = array('USD'/*, 'EUR', 'GBP', 'RUB'*/);
                $tempArray = array();
                foreach($currencies as $currency) {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://indacoin.com/api/GetCoinConvertAmount/'.$currency.'/DCN/100/dentacoin',
                        CURLOPT_SSL_VERIFYPEER => 0
                    ));
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    $resp = json_decode(curl_exec($curl));
                    curl_close($curl);

                    if(!empty($resp))   {
                        $tempArray[$currency] = 1 / (int)((int)$resp / 100);
                    }
                }

                if(!empty($tempArray)) {
                    return $tempArray;
                } else {
                    return 0;
                }
            } else if (getenv('EXTERNAL_PROVIDER_PRICE_READING_TYPE') == 'coingecko') {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => "https://api.coingecko.com/api/v3/coins/dentacoin",
                    CURLOPT_SSL_VERIFYPEER => 0
                ));
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $resp = json_decode(curl_exec($curl));
                curl_close($curl);
                if(!empty($resp))   {
                    if(!empty($resp->market_data->current_price))  {
                        return array(
                            'USD' => $resp->market_data->current_price->usd
                        );
                    }else {
                        return 0;
                    }
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getCountry($client_ip)  {
        try {
            return mb_strtolower(trim(@file_get_contents('https://ipinfo.io/' . $client_ip . '/country')));
        } catch(Exception $e) {
            return false;
        }
    }

    public function saveAddress($dcnAddress, $dcnAddressLabel) {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/add-wallet-address',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'dcn_address' => $dcnAddress,
                'dcn_address_label' => $dcnAddressLabel,
                'main' => false
            ),
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        } else {
            return false;
        }
    }

    public function deleteAddress($dcnAddressId) {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/delete-wallet-address',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => array(
                'wallet_id' => $dcnAddressId
            ),
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        } else {
            return false;
        }
    }

    public function getAddresses() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/wallet-addresses/' . session('logged_user')['id'],
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function checkUserIdAndToken($id, $token)  {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . $token;
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/check-user-info/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => array(
                'user_id' => $id
            )
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            return $resp;
        }else {
            return false;
        }
    }

    public function validateAccessToken() {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/validate-access-token/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!empty($resp))   {
            return $resp;
        } else {
            return false;
        }
    }

    public function getUnseenNotificationsCount($returnAsJson = false)  {
        $header = array();
        $header[] = 'Accept: */*';
        $header[] = 'Authorization: Bearer ' . session('logged_user')['token'];
        $header[] = 'Cache-Control: no-cache';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => getenv('API_DOMAIN').'/api/unseen-notifications-count/',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $header
        ));

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($resp))   {
            if ($returnAsJson) {
                return response()->json($resp);
            } else {
                return $resp;
            }
        } else {
            return false;
        }
    }
}
