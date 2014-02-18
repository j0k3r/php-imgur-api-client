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
     * @return \Imgur\Api\Model\Conversation|array
     */
    public function conversationList() {
        $parameters = $this->get('conversations');

        $conversations = array();
        
        foreach($parameters['data'] as $parameter) {
            $conversations[] = new Model\Conversation($parameter);
        }
        
        return $conversations;
    }
    
    /**
     * Get information about a specific conversation. Includes messages.
     * 
     * @param string $conversationId
     * @return \Imgur\Api\Model\Conversation|array
     */
    public function conversation($conversationId) {
        $parameters = $this->get('conversations/'.$conversationId);
        
        $conversation = new Model\Conversation($parameters['data']);
        
        return $conversation;        
    }
    
    /**
     * Create a new message. Check the link for the structure of the $parameters 
     * (current structure should contain the 'recipient' and 'body' keys ('recipient' being the username of the receiver))
     *  
     * @param array $parameters
     * @link https://api.imgur.com/endpoints/conversation#message-create
     * @return \Imgur\Api\Model\Basic
     */
    public function messageCreate($parameters) {
        $parameters = $this->post('conversations/'.$parameters['recipient'], $parameters);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Delete a conversation by the given ID.
     * 
     * @param string $conversationId
     * @return \Imgur\Api\Model\Basic
     */
    public function conversationDelete($conversationId) {
        $parameters = $this->delete('conversations/'.$conversationId);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Report a user for sending messages that are against the Terms of Service.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function reportSender($username) {
        $parameters = $this->post('conversations/report/'.$username);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Report a user for sending messages that are against the Terms of Service.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function blockSender($username) {
        $parameters = $this->post('conversations/block/'.$username);
        
        return new Model\Basic($parameters);
    }
}