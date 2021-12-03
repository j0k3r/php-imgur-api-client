<?php

namespace Imgur\Middleware;

use Psr\Http\Message\RequestInterface;

class AuthMiddleware
{
    /** @var array|null */
    private $token;
    private string $clientId;

    /**
     * Middleware that add Authorization header.
     *
     * @param array|null $token
     */
    public function __construct($token, string $clientId)
    {
        $this->token = $token;
        $this->clientId = $clientId;
    }

    /**
     * Add Authorization header to the request.
     */
    public function addAuthHeader(RequestInterface $request): RequestInterface
    {
        if (!empty($this->token['access_token'])) {
            return $request->withHeader(
                'Authorization',
                'Bearer ' . $this->token['access_token']
            );
        }

        return $request->withHeader(
            'Authorization',
            'Client-ID ' . $this->clientId
        );
    }
}
