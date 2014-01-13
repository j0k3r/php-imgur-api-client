<?php

namespace Imgur\HttpClient\Listener;

use Guzzle\Common\Event;

class AuthListener {
    
    private $token;

    public function __construct($token) {
        $this->token = $token;
    }

    public function onRequestBeforeSend(Event $event) {
        $event['request']->setHeader(
            'Authorization',
            sprintf('Bearer '.$this->token['access_token'] )
        );
    }
}
