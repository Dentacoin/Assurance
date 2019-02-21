<?php

namespace App\Http\Controllers;

use App\CalculatorParameter;
use App\Http\Controllers\Admin\CalculatorParametersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;

class HomeController extends Controller
{
    public function getView()   {
        $session_arr = [
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijc0MTY5NTA2YTNkMzc2YTJiZDllOTc0OTc3ZjNiNzlmOTk2MWUwZDRjMDJjYTIwZTE2YTI2Njc2MDBhNTNjZjgwZWI5YzcyNzRmZWY5MjAyIn0.eyJhdWQiOiIxIiwianRpIjoiNzQxNjk1MDZhM2QzNzZhMmJkOWU5NzQ5NzdmM2I3OWY5OTYxZTBkNGMwMmNhMjBlMTZhMjY2NzYwMGE1M2NmODBlYjljNzI3NGZlZjkyMDIiLCJpYXQiOjE1NDkyODMwNzAsIm5iZiI6MTU0OTI4MzA3MCwiZXhwIjoxNTgwODE5MDcwLCJzdWIiOiI2ODYyMiIsInNjb3BlcyI6W119.AIqHr8OSCVAukz9Tte5vlutFtrpCzZ1QazcRw8qunfxJ_sn3OHlrCAQ1BFDD2rsZFn5HuDzVnhGUEbic5I2XzTFIMVZApHbtCjomJKMdFGZUgBAVZ8r7_8Kej2KIPoxCuODFP3_qTkijTJopsgJ4ejLcvv8kEdR-5Ss2Tdq0MAA2YKtd1tAh2isivD3I4hPg42kAOr3Zp6hhB3DCzr67IcfJkP_Wty0AKH0fz2jnYrfd81iDi_gFBmGy7Zt9woyMkeAYx0Fz74be4Ai9h855rz1EWWzFW7_FhOBJCXpJNl720h4ym_aTbwzuSpZm0WIfsN9gy2BxHiQ5KZO4BYAh2yqi3yykYqv5wHIi6K8JO-H8g0oaJXGjsupTWu3YXn8TXZct36OhZL6sM5jTYLp9Ij3hgL_VL6YzTG5WlSVKFDreQirivLrwELfmppZc1mwZGsvfvAdZTFKFkI0fmLUg9scIhAuvzOASdantv7Y8uMirlMBb2SRxR99xlcc4_NNb3QQFQoYUKcfMCiOGwoiWzOd8Ofx_EqyVSeoHEV5Y0YOjPpUN_MfNMhP8AULTNQFMinelHzvDLhjMUIlTwCQpA8DQRiQTKihRnmKGE6iOT6NaS5P9JmyCY8Uik0pjlTL47D9z0BSH_BuWIqxZnQb1j3cXKh915qwChh2iXyy-xiw',
            'id' => '68622',
            'type' => 'dentist',
            'have_contracts' => true
        ];
        session(['logged_user' => $session_arr]);

        /*$session_arr = [
            'token' => 'enYh2KchyDUJdfslM9Xfd67qhjPQnIOKzx4ozEnqJECECb04PA1EFOs2KDYg',
            'id' => '68244',
            'type' => 'patient',
            'have_contracts' => true
        ];
        session(['logged_user' => $session_arr]);*/

        if((new UserController())->checkDentistSession()) {
            return (new DentistController())->getView();
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

