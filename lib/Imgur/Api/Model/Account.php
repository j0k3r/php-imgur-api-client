<?php

namespace Imgur\Api\Model;

use Imgur\Api\AbstractApi;

/**
 * Model for Accounts
 * 
 * @link https://api.imgur.com/models/account
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Account extends AbstractApi {
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
    
    public function __construct($username) {
        $accountInfo = $this->get('account/'.$this->username);
        
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
     * @var integer 
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
     * @var string
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
     * @var string 
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
     * @var float 
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
     * @var integer
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
     * @var integer|boolean 
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