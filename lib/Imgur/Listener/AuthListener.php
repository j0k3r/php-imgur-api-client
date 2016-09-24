<?php

namespace Imgur\Listener;

use GuzzleHttp\Event\BeforeEvent;

class AuthListener
{
    private $token;
    private $clientId;

    public function __construct($token, $clientId)
    {
        $this->token = $token;
        $this->clientId = $clientId;
    }

    public function before(BeforeEvent $event)
    {
        $request = $event->getRequest();

        if (is_array($this->token) && !empty($this->token['access_token'])) {
            $request->setHeader(
                'Authorization',
                'Bearer ' . $this->token['access_token']
            );
        } else {
            $request->setHeader(
                'Authorization',
                'Client-ID ' . $this->clientId
            );
        }
    }
}
