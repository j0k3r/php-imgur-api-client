<?php

namespace Imgur\Api\Model;

/**
 * Model for Message
 * 
 * @link https://api.imgur.com/models/message
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Message {
    /**
     * The ID for the message
     * 
     * @var string 
     */
    private $id;
    
    /**
     * Account Username of person sending the message
     * @var string 
     */
    private $from;
    
    /**
     * The account ID of the person sending the message
     * @var integer 
     */
    private $accountId;
    
    /**
     * The account id of the person whom received the message
     * @var integer 
     */
    private $recipientAccountId;
    
    /**
     * The subject of the message.
     * @var string 
     */
    private $subject;
    
    /**
     * The text of body of the message
     * @var string 
     */
    private $body;
    
    /**
     * The formatted time of the message from now.
     * @var string 
     */
    private $timestamp;
    
    /**
     * Parent ID is the message id first message in the thread
     * @var integer 
     */
    private $parentId;
    
    public function __construct($parameters) {
        if(!empty($parameters['data'])) {
            $parameters = $parameters['data'];
        }
        
        $this->setAccountId($parameters['account_id'])
             ->setBody($parameters['body'])
             ->setFrom($parameters['from'])
             ->setId($parameters['id'])
             ->setParentId($parameters['parent_id'])
             ->setRecipientAccountId($parameters['recipient_account_id'])
             ->setSubject($parameters['subject'])
             ->setTimestamp($parameters['timestamp']);
       
        return $this;        
    }
    
    /**
     * The ID for the message
     * 
     * @return string
     */
    public function getId() {
        
        return $this->id;
    }

    /**
     * The ID for the message
     * 
     * @param string $id
     * @return \Imgur\Api\Model\Message
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Account Username of person sending the message
     * 
     * @return string
     */
    public function getFrom() {
        
        return $this->from;
    }

    /**
     * Account Username of person sending the message
     * 
     * @param string $from
     * @return \Imgur\Api\Model\Message
     */
    public function setFrom($from) {
        $this->from = $from;
        
        return $this;
    }

    /**
     * The account ID of the person sending the message
     * 
     * @return integer
     */
    public function getAccountId() {
        
        return $this->accountId;
    }

    /**
     * The account ID of the person sending the message
     * 
     * @param integer $accountId
     * @return \Imgur\Api\Model\Message
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
        
        return $this;
    }

    /**
     * The account id of the person whom received the message
     * 
     * @return integer
     */
    public function getRecipientAccountId() {
        
        return $this->recipientAccountId;
    }

    /**
     * The account id of the person whom received the message
     * 
     * @param integer $recipientAccountId
     * @return \Imgur\Api\Model\Message
     */
    public function setRecipientAccountId($recipientAccountId) {
        $this->recipientAccountId = $recipientAccountId;
        
        return $this;
    }

    /**
     * The subject of the message.
     * 
     * @return string
     */
    public function getSubject() {
        
        return $this->subject;
    }

    /**
     * The subject of the message.
     * 
     * @param string $subject
     * @return \Imgur\Api\Model\Message
     */
    public function setSubject($subject) {
        $this->subject = $subject;
        
        return $this;
    }

    /**
     * The text of body of the message
     * 
     * @return string
     */
    public function getBody() {
        
        return $this->body;
    }

    /**
     * The text of body of the message
     * 
     * @param string $body
     * @return \Imgur\Api\Model\Message
     */
    public function setBody($body) {
        $this->body = $body;
        
        return $this;
    }

    /**
     * The formatted time of the message from now.
     * 
     * @return string
     */
    public function getTimestamp() {
        
        return $this->timestamp;
    }

    /**
     * The formatted time of the message from now.
     * 
     * @param string $timestamp
     * @return \Imgur\Api\Model\Message
     */
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
        
        return $this;
    }

    /**
     * Parent ID is the message id first message in the thread
     * 
     * @return string
     */
    public function getParentId() {
        
        return $this->parentId;
    }

    /**
     * Parent ID is the message id first message in the thread
     * 
     * @param string $parentId
     * @return \Imgur\Api\Model\Message
     */
    public function setParentId($parentId) {
        $this->parentId = $parentId;
        
        return $this;
    }
}