<?php

namespace Imgur\Middleware;

use Psr\Http\Message\RequestInterface;

class AuthMiddleware
{
    private $token;
    private $clientId;

    /**
     * Middleware that add Authorization header.
     *
     * @param array  $token
     * @param string $clientId
     */
    public function __construct($token, $clientId)
    {
        $this->token = $token;
        $this->clientId = $clientId;
    }

    /**
     * Add Authorization header to the request.
     *
     * @param RequestInterface $request
     */
    public function addAuthHeader(RequestInterface $request)
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
