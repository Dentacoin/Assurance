<?php

namespace App\Http\Middleware;
use Symfony\Component\HttpFoundation\Cookie;
use Carbon\Carbon;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'save-public-key', 'save-mobile-id', 'send-push-notification', 'cancel-contracts', 'get-scanning-data', 'request-contract-status-change', 'info/*', 'mark-contract-as-processing', 'authenticate-user', 'save-message-relay', 'get-message-relays/*'
    ];

    protected function addCookieToResponse($request, $response) {
        $config = config('session');

        $response->headers->setCookie(
            new Cookie(
                'XSRF-TOKEN', $request->session()->token(), Carbon::now()->getTimestamp() + 60 * $config['lifetime'],
                $config['path'], $config['domain'], $config['secure'], true
            )
        );

        return $response;
    }
}
