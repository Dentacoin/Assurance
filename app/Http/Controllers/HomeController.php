<?php

namespace App\Http\Controllers;

use App\CalculatorParameter;
use App\Http\Controllers\Admin\CalculatorParametersController;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;

class HomeController extends Controller
{
    public function getView()   {
        /*$session_arr = [
            'token' => 'enYh2KchyDUJdfslM9Xfd67qhjPQnIOKzx4ozEnqJECECb04PA1EFOs2KDYg',
            'id' => '68856',
            'type' => 'dentist'
        ];
        session(['logged_user' => $session_arr]);*/

        /*$session_arr = [
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjdiY2ZiOGMzODk5Y2ZmMmQ4ODE2NjNkMjE2YjIzMmRjMzE5ZTA0MWIxYzJhZTA4MzFkZGNkYzg3MzE5MDY4ZTRhMjJkOWViZDk4NGJhYmI4In0.eyJhdWQiOiIxIiwianRpIjoiN2JjZmI4YzM4OTljZmYyZDg4MTY2M2QyMTZiMjMyZGMzMTllMDQxYjFjMmFlMDgzMWRkY2RjODczMTkwNjhlNGEyMmQ5ZWJkOTg0YmFiYjgiLCJpYXQiOjE1NTA4MzQ5MzMsIm5iZiI6MTU1MDgzNDkzMywiZXhwIjoxNTgyMzcwOTMzLCJzdWIiOiI2ODg1OCIsInNjb3BlcyI6W119.fS2rcrIRGlrV8z47SUas6IE2XepMmSTnT6hCB2YFUxSRAUPoi3bZRuu4dKkCTTfLS8PKE4CaHWCruasjbxAL_1NxK94dC-dKdgiT_BvEjqkkNKBnkrCDZDFzc9XluQu4GcqGyzXlyLVN8EpYbvbQDgMJ9hVgGTYVJDK6Zombwev8mMMBGS-DDteria34rsJPV8OpEXW5k1Ur3v6Ip-63OxNZ7Ae1kuOMjSMCrjDeMcziJEPTibIhrlB4TGvRblW6RWuTxF-KJPgDLXjrFhYpCjdLsXNN-scdsOuqGaTnqWMeGN0Iu0PXuDN1vQY60v18lf7XePQ-yNJ7lHf7axWpQycAybcKQwg3GXp7mutL0qEhMm3wr_4UW3aY75JPYguIeZ_uJm-SdUpxJ86PntCh8YnIASS-bHBVs89ClmGKhKt1UhlnG6cDTnqxgfOY7bcSf-DpwnvY9cABlQYqHidCzuS245MjTBb-UMHKtXcbXE9xaC-vF5IHcex0BOXpn5iIWbTTvC11-0mkMruKgBus2DC6ibbVanOitL7krUPx6jOlj6JwgHHiXrLNLvMUEEWp-1tYl31jZKqu0bWKGSDjKTZw4YGGimhEPBCVF4N2cJ2ttHBFuk-Ofk82PmCpOhWSN5rk8AyfoQ-hEF83W-vQgkjRrpe6L1CUYDai4xeR8YQ',
            'id' => '68858',
            'type' => 'patient'
        ];
        session(['logged_user' => $session_arr]);*/

        if((new UserController())->checkDentistSession()) {
            if(TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->first()) {
                return (new DentistController())->getContractsView();
            } else {
                return (new DentistController())->getNoContractsView();
            }
        } else if((new UserController())->checkPatientSession()) {
            return redirect()->route('patient-access');
        } else {
            $testimonials = DB::connection('mysql2')->table('user_expressions')->leftJoin('media', 'user_expressions.media_id', '=', 'media.id')->select('user_expressions.*', 'media.name as media_name', 'media.alt as media_alt')->where('visible_assurance', 1)->orderByRaw('user_expressions.order_id ASC')->get()->toArray();
            return view('pages/homepage', ['testimonials' => $testimonials]);
        }
    }

    public function redirectToHome() {
        return redirect()->route('home');
    }

    protected function getDentistView()   {
        return view('pages/dentist-test', []);
    }

    protected function getPatientView()   {
        return view('pages/patient-test', []);
    }

    protected function getCalculatorHtml(Request $request) {
        $params = [];
        if(!empty($request->input('patients_number')) && !empty($request->input('params_type') && !empty($request->input('country'))) && !empty($request->input('currency'))) {
            $params['patients_number'] = $request->input('patients_number');
            $params['country'] = $request->input('country');
            $params['currency'] = $request->input('currency');

            $params['param_gd'] = false;
            $params['param_cd'] = false;
            $params['param_id'] = false;

            switch($request->input('params_type')) {
                case 'param_gd_cd_id':
                    $params['param_gd'] = true;
                    $params['param_cd'] = true;
                    $params['param_id'] = true;
                    break;
                case 'param_gd_cd':
                    $params['param_gd'] = true;
                    $params['param_cd'] = true;
                    break;
                case 'param_gd_id':
                    $params['param_gd'] = true;
                    $params['param_id'] = true;
                    break;
                case 'param_cd_id':
                    $params['param_cd'] = true;
                    $params['param_id'] = true;
                    break;
                case 'param_gd':
                    $params['param_gd'] = true;
                    break;
                case 'param_cd':
                    $params['param_cd'] = true;
                    break;
                case 'param_id':
                    $params['param_id'] = true;
                    break;
            }
        }

        $view = view('partials/calculator', ['parameters' => (new CalculatorParametersController())->getAllCalculatorParameters(), 'currencies' => Controller::currencies, 'params' => $params]);
        $view = $view->render();
        return response()->json(['success' => $view]);
    }

    function getCalculatorResult(Request $request) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.coinmarketcap.com/v1/ticker/dentacoin/?convert=' . $request->input('currency'),
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        $currency = 0;
        switch($request->input('currency')) {
            case 'USD':
                $currency = (float)$resp[0]->price_usd;
                break;
            case 'EUR':
                $currency = (float)$resp[0]->price_eur;
                break;
            case 'GBP':
                $currency = (float)$resp[0]->price_gbp;
                break;
            case 'RUB':
                $currency = (float)$resp[0]->price_rub;
                break;
            case 'INR':
                $currency = (float)$resp[0]->price_inr;
                break;
            case 'CNY':
                $currency = (float)$resp[0]->price_cny;
                break;
            case 'JPY':
                $currency = (float)$resp[0]->price_jpy;
                break;
        }

        $avg_premium = CalculatorParameter::where(array('id' => $request->input('country')))->first();
        $dcn_result = ((($request->input('patients_number') * 240) / 12) * $avg_premium[$request->input('params_type')]) / (float)$resp[0]->price_usd;

        $view = view('partials/calculator-result', ['result' => $dcn_result*$currency, 'currency_symbol' => $request->input('currency')]);
        $view = $view->render();
        return response()->json(['success' => $view]);
    }
}

