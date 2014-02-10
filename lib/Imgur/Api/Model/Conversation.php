<?php

namespace Imgur\Api\Model;

/**
 * Model for Conversation. 
 * This model is not documented by Imgur, but it is implied by the responses 
 * received for the https://api.imgur.com/endpoints/conversation#conversation-list API call
 * 
 * @link https://api.imgur.com/endpoints/conversation#conversation-list
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Conversation {
    private $id;
    private $withAccount;
    private $withAccountId;
    private $lastMessagePreview;
    private $messageCount;
    private $dateTime;
    private $messages;
    
    public function __construct($parameters) {
        $this->setDateTime($parameters['datetime'])
             ->setId($parameters['id'])
             ->setLastMessagePreview($parameters['last_message_preview'])
             ->setMessageCount($parameters['message_count'])
             ->setWithAccount($parameters['with_account'])
             ->setWithAccountId($parameters['with_account_id']);
        
        if(!empty($parameters['messages'])) {
            $messages = array();
            
            foreach($parameters['messages'] as $message) {
                $messages[] = new Message($message);
            }
            
            $this->setMessages($messages);
        }
        
        return $this;
    }
    
    public function getId() {
        
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    public function getWithAccount() {
        
        return $this->withAccount;
    }

    public function setWithAccount($withAccount) {
        $this->withAccount = $withAccount;
        
        return $this;
    }

    public function getWithAccountId() {
        
        return $this->withAccountId;
    }

    public function setWithAccountId($withAccountId) {
        $this->withAccountId = $withAccountId;
        
        return $this;
    }

    public function getLastMessagePreview() {
        
        return $this->lastMessagePreview;
    }

    public function setLastMessagePreview($lastMessagePreview) {
        $this->lastMessagePreview = $lastMessagePreview;
        
        return $this;
    }

    public function getMessageCount() {
        
        return $this->messageCount;
    }

    public function setMessageCount($messageCount) {
        $this->messageCount = $messageCount;
        
        return $this;
    }

    public function getDateTime() {
        
        return $this->dateTime;
    }

    public function setDateTime($dateTime) {
        $this->dateTime = $dateTime;
        
        return $this;
    }    
    
    public function getMessages() {
        
        return $this->messages;
    }
    
    public function setMessages($messages) {
        $this->messages = $messages;
        
        return $this;
    }
}