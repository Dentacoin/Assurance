<?php

namespace App\Http\Middleware;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use Closure;

class HandlePatientSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $user_controller = new UserController();
        if(!$user_controller->checkSession() && !array_key_exists('token', $request->input()) && !array_key_exists('email', $request->input())) {
            //NOT LOGGED AND NOT TRYING TO LOG IN
            return response((new PatientController())->getNotLoggedView());
        } else if($user_controller->checkSession() && session('logged_user')['type'] != 'patient') {
            //if logged user with other role trying to access routes protected by this middleware
            return redirect()->route('home', ['cross-login' => true]);
        } else {
            return $next($request);
        }
    }
}
