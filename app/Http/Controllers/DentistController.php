<?php

namespace App\Http\Controllers;

use App\CalculatorParameter;
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
        $current_logged_dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']);
        $calculator_proposals = CalculatorParameter::where(array('code' => (new APIRequestsController())->getAllCountries()[$current_logged_dentist->country_id - 1]->code))->get(['param_gd_cd_id', 'param_gd_cd', 'param_gd_id', 'param_cd_id', 'param_gd', 'param_cd', 'param_id'])->first()->toArray();
        if(!empty($contract)) {
            return view('pages/logged-user/dentist/single-contract-view-'.$contract->status, ['contract' => $contract, 'calculator_proposals' => $calculator_proposals]);
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
        $calculator_proposals = CalculatorParameter::where(array('code' => (new APIRequestsController())->getAllCountries()[$current_logged_dentist->country_id - 1]->code))->get(['param_gd_cd_id', 'param_gd_cd', 'param_gd_id', 'param_cd_id', 'param_gd', 'param_cd', 'param_id'])->first()->toArray();
        $params = ['countries' => (new APIRequestsController())->getAllCountries(), 'current_logged_dentist' => $current_logged_dentist, 'calculator_proposals' => $calculator_proposals];
        if(!empty(Input::get('renew-contract'))) {
            $contract = TemporallyContract::where(array('slug' => Input::get('renew-contract'), 'status' => 'cancelled'))->get()->first();
            if(!empty($contract)) {
                $params['renew_contract'] = $contract;
            } else {
                return abort(404);
            }
        }
        return view('pages/logged-user/dentist/create-contract', $params);
    }

    protected function register(Request $request) {
        $customMessages = [
            'dentist-or-practice-name.required' => 'Dentist or Practice Name is required.',
            'email.required' => 'Email address is required.',
            'password.required' => 'Password is required.',
            'repeat-password.required' => 'Repeat password is required.',
            'work-type.required' => 'Work type is required.',
            'country-code.required' => 'Country is required.',
            'address.required' => 'City, Street is required.',
            'phone.required' => 'Phone number is required.',
            'website.required' => 'Website is required.',
            'specializations.required' => 'Specialization is required.',
            'captcha.required' => 'Captcha is required.',
            'captcha.captcha' => 'Please enter correct captcha.'
        ];
        $this->validate($request, [
            'dentist-or-practice-name' => 'required|max:250',
            'email' => 'required|max:100',
            'password' => 'required|max:50',
            'repeat-password' => 'required|max:50',
            'work-type' => 'required',
            'country-code' => 'required',
            'address' => 'required|max:300',
            'phone' => 'required|max:50',
            'website' => 'required|max:250',
            'specializations' => 'required',
            'captcha' => 'required|captcha|max:10'
        ], $customMessages);



        $data = $request->input();
        $files = $request->file();

        var_dump('koz');
        die();

        //check email validation
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))   {
            return redirect()->route('home')->with(['error' => 'Your form was not sent. Please try again with valid email.']);
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
                        return redirect()->route('home', ['slug' => $request->input('post-slug')])->with(['error' => 'Your form was not sent. Files can be only with with maximum size of '.number_format(MAX_UPL_SIZE / 1048576).'MB. Please try again.']);
                    }
                    //checking file format
                    if(!in_array(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION), $allowed)) {
                        return redirect()->route('home')->with(['error' => 'Your form was not sent. Files can be only with .png, .jpg, .jpeg, .svg, .bmp formats. Please try again.']);
                    }
                    //checking if error in file
                    if($file->getError()) {
                        return redirect()->route('home')->with(['error' => 'Your form was not sent. There is error with one or more of the files, please try with other files. Please try again.']);
                    }
                }
            }
        } /*else {
            return redirect()->route('home')->with(['error' => 'Please select avatar and try again.']);
        }*/

        //handle the API response
        $api_response = (new APIRequestsController())->dentistRegister($data, $files);
        if($api_response['success']) {
            return redirect()->route('home')->with(['success' => true, 'popup-html' => '<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">DENTIST PROFILE VERIFICATION</div><div class="padding-top-20 padding-bottom-15 fs-20">Thank you for submitting your registration form! Our onboarding team will now assess the data you have provided to ensure that  you are a real dentist.</div><div class="padding-bottom-25 fs-20">You will receive an email notification as soon as the verification processis completed. Then you will be able to use all Dentacoin Assurance funtionlities.</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0)" class="white-blue-green-btn min-width-200 close-popup">OK</a></div></div>']);
        } else {
            return redirect()->route('home')->with(['errors_response' => $api_response['errors']]);
        }
    }

    protected function login(Request $request) {
        $customMessages = [
            'email.required' => 'Email address is required.',
            'password.required' => 'Password is required.',
        ];
        $this->validate($request, [
            'email' => 'required|max:100',
            'password' => 'required|max:50'
        ], $customMessages);

        $data = $request->input();

        //check email validation
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('home')->with(['error' => 'Your form was not sent. Please try again with valid email.']);
        }

        //handle the API response
        $api_response = (new APIRequestsController())->dentistLogin($data);

        if($api_response['success']) {
            $approved_statuses = array('approved', 'pending');
            if($api_response['data']['self_deleted'] != NULL) {
                return redirect()->route('home')->with(['error' => 'This account is deleted, you cannot log in with this account anymore.']);
            } else if(!in_array($api_response['data']['status'], $approved_statuses)) {
                return redirect()->route('home')->with(['error' => 'This account is not approved by Dentacoin team yet, please try again later.']);
            } else {
                //check if waiting invite dentist rewards
                $reward = InviteDentistsReward::where(array('dentist_email' => $data['email'], 'dentist_registered_and_approved' => 0, 'payed_on' => NULL))->get()->first();

                if(!empty($reward)) {
                    $reward->dentist_registered_and_approved = true;
                    $reward->save();
                }

                $session_arr = [
                    'token' => $api_response['token'],
                    'id' => $api_response['data']['id'],
                    'type' => 'dentist'
                ];

                session(['logged_user' => $session_arr]);
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('home')->with(['errors_response' => $api_response['errors']]);
        }
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
        ], $customMessages);

        $data = $request->input();
        $files = $request->file();

        //check email validation
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('create-contract')->with(['error' => 'Your form was not sent. Please try again with valid email.']);
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
                        return redirect()->route('create-contract')->with(['error' => 'Your form was not sent. Files can be only with with maximum size of '.number_format(MAX_UPL_SIZE / 1048576).'MB. Please try again.']);
                    }
                    //checking file format
                    if(!in_array(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION), $allowed)) {
                        return redirect()->route('create-contract')->with(['error' => 'Your form was not sent. Files can be only with .png, .jpg, .jpeg, .svg, .bmp formats. Please try again.']);
                    }
                    //checking if error in file
                    if($file->getError()) {
                        return redirect()->route('create-contract')->with(['error' => 'Your form was not sent. There is error with one or more of the files, please try with other files. Please try again.']);
                    }
                }
            }
        }

        //if user selected avatar or entered dcn_address both for first time
        if(!empty($files['image']) || !empty($data['address'])) {
            $post_fields_arr = array();
            if(!empty($files['image'])) {
                $post_fields_arr['avatar'] = curl_file_create($files['image']->getPathName(), 'image/'.pathinfo($files['image']->getClientOriginalName(), PATHINFO_EXTENSION), $files['image']->getClientOriginalName());
            }
            if(!empty($data['address'])) {
                $post_fields_arr['dcn_address'] = trim($data['address']);
            }

            //handle the API response
            $api_response = (new APIRequestsController())->updateUserData($post_fields_arr);
            if(!$api_response) {
                return redirect()->route('create-contract')->with(['errors_response' => $api_response['errors']]);
            }
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < 62; $i+=1) {
            $random_string .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        $random_string = $random_string.time();

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

        $temporally_contract = new TemporallyContract();
        $temporally_contract->dentist_id = session('logged_user')['id'];
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

        if($temporally_contract->id) {
            //send email
            $sender = (new APIRequestsController())->getUserData(session('logged_user')['id']);
            $body = '<!DOCTYPE html><html><head></head><body style="font-size: 16px;"><div>Dear '.$temporally_contract->patient_fname.' '.$temporally_contract->patient_lname.',<br><br><br>I have created an individualized Assurance Contract for you. It entitles you to prevention-focused dental services against an affordable monthly premium in Dentacoin (DCN) currency*.<br><br>It’s very easy to start: just click on the button below, sign up, check my proposal and follow the instructions if you are interested:<br><br><br><a href="'.route('contract-proposal', ['slug' => $temporally_contract->slug]).'" style="font-size: 20px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE YOUR ASSURANCE CONTRACT</a><br><br><br>Looking forward to seeing you onboard!<br><br>Regards,<br><b>'.$sender->name.'</b><br><br><br><i style="font-size: 13px;">* Dentacoin is the first dental cryptocurrency which can be earned through the Dentacoin tools, used as a means of payment for dental services and assurance fees, and exchanged to any other crypto or traditional currency.</i></div></body></html>';

            Mail::send(array(), array(), function($message) use ($body, $data, $sender) {
                $message->to($data['email'])->subject('Dentist '.$sender->name.' invited you to join Dentacoin Assurance');
                $message->from($sender->email, $sender->name)->replyTo($sender->email, $sender->name);
                $message->setBody($body, 'text/html');
            });

            if(count(Mail::failures()) > 0) {
                return redirect()->route('create-contract')->with(['error' => 'Something went wrong with sending contract via email. Please try again later.']);
            } else {
                return redirect()->route('dentist-contract-view', ['slug' => $temporally_contract->slug])->with(['success' => true, 'popup-html' => '<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">SUCCESSFULLY SENT.</div><div class="padding-top-20 padding-bottom-25 fs-20">Yоur contract sample was successfully sent. You will be notified via email when '.trim($data['fname']).' '.trim($data['lname']).' signs the contract.</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0)" class="white-blue-green-btn min-width-200 close-popup">OK</a></div></div>']);
            }
        } else {
            return redirect()->route('create-contract')->with(['error' => 'Something went wrong with sending contract via email. Please try again later.']);
        }
    }
}
