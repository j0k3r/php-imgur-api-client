<?php

namespace Imgur\Api\Model;

/**
 * Model for Accounts
 * 
 * @link https://api.imgur.com/models/account
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Account {
    /**
     * The account id for the username requested.
     * 
     * @var integer 
     */
    private $id;
    
    /**
     * The account username
     * 
     * @var string
     */
    private $url;
    
    /**
     * A basic description the user has filled out
     * 
     * @var string 
     */
    private $bio;
    
    /**
     * The reputation for the account, in it's numerical format.
     * 
     * @var float 
     */
    private $reputation;
    
    /**
     * The epoch time of account creation
     *  
     * @var integer
     */
    private $created;
    
    /**
     * False if not a pro user, their expiration date if they are.
     * 
     * @var integer|boolean 
     */
    private $proExpiration; 
    
    /**
     * Build the Account object based on an array
     * 
     * @param array $accountInfo
     * @return \Imgur\Api\Model\Account
     */
    public function __construct($accountInfo) {
        if(!empty($accountInfo['data'])) {
            $accountInfo = $accountInfo['data'];
        }
        
        $this->setId($accountInfo['id'])
             ->setUrl($accountInfo['url'])
             ->setBio($accountInfo['bio'])
             ->setReputation($accountInfo['reputation'])
             ->setCreated($accountInfo['created'])
             ->setProExpiration($accountInfo['pro_expiration']);
        
        return $this;
    }

    /**
     * The account id for the username requested.
     * 
     * @param integer $id
     */    
    public function setId($id) {
        $this->id = $id; 
        
        return $this;
    }

    /**
     * The account id for the username requested.
     * 
     * @return integer|null
     */    
    public function getId() {
        
        return $this->id;
    }

    /**
     * The account username
     * 
     * @param string $url
     */    
    public function setUrl($url) {
        $this->url = $url;
        
        return $this;        
    }

    
    /**
     * The account username
     * 
     * @return string|null
     */    
    public function getUrl() {
        
        return $this->url;
    }

    /**
     * A basic description the user has filled out
     * 
     * @param string $bio
     */
    public function setBio($bio) {
        $this->bio = $bio;
        
        return $this;        
    }
    
    /**
     * A basic description the user has filled out
     * 
     * @return string|null
     */  
    public function getBio() {
        
        return $this->bio;
    }    
    
    /**
     * The reputation for the account, in it's numerical format.
     * 
     * @param float $reputation
     */
    public function setReputation($reputation) {
        $this->reputation = $reputation;
        
        return $this;        
    }
    
    /**
     * The reputation for the account, in it's numerical format.
     * 
     * @return float|null
     */
    public function getReputation() {
        
        return $this->reputation;
    }  
    
    /**
     * The epoch time of account creation
     *  
     * @param integer $created
     */
    public function setCreated($created) {
        $this->created = $created;
        
        return $this;        
    }
    
    /**
     * The epoch time of account creation
     *  
     * @return integer
     */
    public function getCreated() {
        
        return $this->created;
    }  
    
    /**
     * False if not a pro user, their expiration date if they are.
     * 
     * @param integer|boolean $proExpiration
     */
    public function setProExpiration($proExpiration) {
        $this->proExpiration = $proExpiration;
        
        return $this;        
    }
    
    /**
     * False if not a pro user, their expiration date if they are.
     * 
     * @return integer|boolean 
     */
    public function getProExpiration() {
        
        return $this->proExpiration;
    }     
}