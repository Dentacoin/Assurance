<?php

namespace App\Http\Controllers;

use App\CalculatorParameter;
use App\Http\Controllers\Admin\CalculatorParametersController;
use App\TemporallyContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    public function getView()   {
        if((new UserController())->checkDentistSession()) {
            if(TemporallyContract::where(array('dentist_id' => session('logged_user')['id']))->get()->first()) {
                return (new DentistController())->getContractsView();
            } else {
                return (new DentistController())->getNoContractsView();
            }
        } else if((new UserController())->checkPatientSession()) {
            if(!empty(Input::get('cross-login'))) {
                return redirect()->route('patient-access', ['cross-login' => true]);
            } else {
                return redirect()->route('patient-access');
            }
        } else {
            die('Maintenance.');
            var_dump((new APIRequestsController())->getTestimonials());
            die();
            return view('pages/homepage', ['testimonials' => (new APIRequestsController())->getTestimonials()]);
        }
    }

    public function redirectToHome() {
        return redirect()->route('home');
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
            CURLOPT_URL => 'https://api.coingecko.com/api/v3/coins/dentacoin',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        $currency = 0;
        switch($request->input('currency')) {
            case 'USD':
                $currency = $resp->market_data->current_price->usd;
                break;
            case 'EUR':
                $currency = $resp->market_data->current_price->eur;
                break;
            case 'GBP':
                $currency = $resp->market_data->current_price->gbp;
                break;
            case 'RUB':
                $currency = $resp->market_data->current_price->rub;
                break;
            case 'INR':
                $currency = $resp->market_data->current_price->inr;
                break;
            case 'CNY':
                $currency = $resp->market_data->current_price->cny;
                break;
            case 'JPY':
                $currency = $resp->market_data->current_price->jpy;
                break;
        }

        $avg_premium = CalculatorParameter::where(array('id' => $request->input('country')))->first();
        $dcn_result = ((($request->input('patients_number') * 240) / 12) * $avg_premium[$request->input('params_type')]) / (float)$resp->market_data->current_price->usd;

        $view = view('partials/calculator-result', ['result' => $dcn_result*$currency, 'currency_symbol' => $request->input('currency')]);
        $view = $view->render();
        return response()->json(['success' => $view]);
    }
}

