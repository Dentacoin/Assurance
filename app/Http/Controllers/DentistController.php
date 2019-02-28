<?php

namespace App\Http\Controllers;

use App\CalculatorParameter;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DentistController extends Controller
{
    public function getNoContractsView() {
        return view('pages/logged-user/dentist/homepage-no-contracts');
    }

    public function getDentistContractView() {
        var_dump('koz');
        die();
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
        return view('pages/logged-user/dentist/create-contract', ['countries' => (new APIRequestsController())->getAllCountries(), 'current_logged_dentist' => $current_logged_dentist, 'calculator_proposals' => $calculator_proposals]);
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
            'specialization.required' => 'Specialization is required.',
            'captcha.required' => 'Captcha is required.',
            'captcha.captcha' => 'Please type the code from the captcha image.'
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
            'specialization' => 'required',
            'captcha' => 'required|captcha|max:5'
        ], $customMessages);

        $data = $request->input();
        $files = $request->file();

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
        } else {
            return redirect()->route('home')->with(['error' => 'Please select avatar and try again.']);
        }

        //handle the API response
        $api_response = (new APIRequestsController())->dentistRegister($data, $files);
        if($api_response['success']) {
            $session_arr = [
                'token' => $api_response['token'],
                'id' => $api_response['data']['id'],
                'type' => 'dentist'
            ];

            session(['logged_user' => $session_arr]);
            return redirect()->route('home');
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
            $session_arr = [
                'token' => $api_response['token'],
                'id' => $api_response['data']['id'],
                'type' => 'dentist'
            ];

            session(['logged_user' => $session_arr]);
            return redirect()->route('home');
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
                return redirect()->route('create-contract')->with(['success' => 'Your contract proposal has been sent successfully to your patient. Once the patient agree with your contract terms and accepts it you will be able to see the contract in your pending contracts section.']);
            }
        } else {
            return redirect()->route('create-contract')->with(['error' => 'Something went wrong with sending contract via email. Please try again later.']);
        }
    }
}
