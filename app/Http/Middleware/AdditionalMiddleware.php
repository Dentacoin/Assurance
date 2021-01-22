<?php

namespace App\Http\Middleware;

use Closure;
use App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

class AdditionalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        /*if(!isset($_COOKIE['testing-dev'])) {
            return response(view('pages/maintenance'));
        }*/
        //$params = $request->route()->parameters();

        $user_controller = new App\Http\Controllers\UserController();
        if($user_controller->checkSession()) {
            $validateAccessTokenResponse = (new App\Http\Controllers\APIRequestsController())->validateAccessToken();
            if (!empty($validateAccessTokenResponse) && is_object($validateAccessTokenResponse) && property_exists($validateAccessTokenResponse, 'success') && !$validateAccessTokenResponse->success) {
                $request->session()->forget('logged_user');

                return Redirect::to(BASE_URL . '?show-login=true');
            }
        }

        $response = $next($request);
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin');

        $route = Route::getRoutes()->match($request);
        if ($route->getName() != 'my-contracts-iframe') {
            $response->headers->set('X-Frame-Options', 'DENY');
        }

        return (new App\Http\Controllers\Controller())->minifyHtml($response);
    }
}
