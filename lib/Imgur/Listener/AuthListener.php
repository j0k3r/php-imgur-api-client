<?php

namespace Imgur\Listener;

use Guzzle\Common\Event;

class AuthListener {
    
    private $token;
    private $clientId;
    
    public function __construct($token, $clientId) {
        $this->token = $token;
        $this->clientId = $clientId;
    }

    public function onRequestBeforeSend(Event $event) {
        if(!empty($this->token['access_token'])) {
            $event['request']->setHeader(
                'Authorization',
                'Bearer '.$this->token['access_token']
            );
        }
        else {
            $event['request']->setHeader(
                'Authorization',
                'Client-ID '.$this->clientId
            );
        }
    }
}
