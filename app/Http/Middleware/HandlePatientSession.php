<?php

namespace App\Http\Middleware;

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
        }

        var_dump('HandlePatientSession');
        die();
        return $next($request);
    }
}
