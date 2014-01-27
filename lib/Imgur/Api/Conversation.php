<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Conversations
 * 
 * @link https://api.imgur.com/endpoints/conversation
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Conversation extends AbstractApi {
    
    /**
     * Get list of all conversations for the logged in user.
     * 
     * @return \Imgur\Api\Model\Message
     */
    public function conversationList() {
        $parameters = $this->get('conversations');
        
        $conversations = array();
        
        foreach($parameters['data'] as $parameter) {
            $conversations[] = new Model\Message($parameter);
        }
        
        return $conversations;
    }
}