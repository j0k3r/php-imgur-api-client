<?php

namespace Imgur\Api\Model;

/**
 * Model for Notification
 * 
 * @link https://api.imgur.com/models/notification
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Notification {
    
    /**
     * The ID for the notification
     * @var string
     */
    private $id;
    
    /**
     * The Account ID for the notification
     * @var integer
     */
    private $accountId;
    
    /**
     * Has the user viewed the image yet?
     * @var boolean
     */
    private $viewed;
    
    /**
     * This can be any other model, currently only using comments and messages
     * @var mixed 
     */
    private $content;
    
    public function __construct($parameters, $content) {
        $this->setId($parameters['id'])
             ->setAccountId($parameters['account_id'])
             ->setViewed($parameters['viewed'])
             ->setContent($content);
        
        return $this;
    }
    
    /**
     * The ID for the notification
     * 
     * @return string
     */
    public function getId() {
        
        return $this->id;
    }

    /**
     * The ID for the notification 
     * 
     * @param string $id
     * @return \Imgur\Api\Model\Notification
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * The Account ID for the notification
     * 
     * @return integer
     */
    public function getAccountId() {
        
        return $this->accountId;
    }

    /**
     * The Account ID for the notification
     *  
     * @param type $accountId
     * @return \Imgur\Api\Model\Notification
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
        
        return $this;        
    }

    /**
     * Has the user viewed the image yet?
     * 
     * @return boolean
     */
    public function getViewed() {
        
        return $this->viewed;
    }

    /**
     * Has the user viewed the image yet?
     * 
     * @param boolean $viewed
     * @return \Imgur\Api\Model\Notification
     */
    public function setViewed($viewed) {
        $this->viewed = $viewed;

        return $this;        
    }
    
    /**
     * This can be any other model, currently only using comments and messages
     * 
     * @return mixed
     */
    public function getContent() {
        
        return $this->content;
    }

    /**
     * This can be any other model, currently only using comments and messages
     * 
     * @param mixed $content
     * @return \Imgur\Api\Model\Notification
     */
    public function setContent($content) {
        $this->content = $content;
        
        return $this;        
    }    
}