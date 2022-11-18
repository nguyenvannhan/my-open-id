<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use League\OAuth2\Server\AuthorizationServer;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController extends Controller
{
    use HandlesOAuthErrors;

    public function __construct(
        protected AuthorizationServer $server,
    ) {
    }

    public function token(ServerRequestInterface $request): mixed
    {
        return $this->withErrorHandling(function () use ($request) {
            return $this->convertResponse(
                $this->server->respondToAccessTokenRequest($request, new Psr7Response)
            );
        });
    }
}
