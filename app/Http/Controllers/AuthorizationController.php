<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Controllers\AuthorizationController as LaravelAuthorizationController;
use Laravel\Passport\Http\Controllers\ConvertsPsrResponses;
use Laravel\Passport\Http\Controllers\RetrievesAuthRequestFromSession;
use Laravel\Passport\TokenRepository;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationController extends LaravelAuthorizationController
{
    use RetrievesAuthRequestFromSession, ConvertsPsrResponses;

    public function authorizeOpenID(
        ServerRequestInterface $psrRequest,
        Request $request,
    ) {
        if (!Auth::check()) {
            throw new AuthenticationException;
        }

        $authRequest = $this->withErrorHandling(function () use ($psrRequest) {
            return $this->server->validateAuthorizationRequest($psrRequest);
        });

        $request->session()->put('authToken', Str::random());

        $request->session()->put('authRequest', $authRequest);

        return $this->approveOpenID($request);
    }

    protected function approveOpenID(Request $request)
    {
        $this->assertValidAuthToken($request);

        $authRequest = $this->getAuthRequestFromSession($request);

        $authRequest->setAuthorizationApproved(true);

        return $this->withErrorHandling(function () use ($authRequest) {
            return $this->convertResponse(
                $this->server->completeAuthorizationRequest($authRequest, new Psr7Response)
            );
        });
    }
}
