<?php

namespace Imgur\Api\Model;

/**
 * Model for Account Settings
 * 
 * @link https://api.imgur.com/models/account_settings
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class AccountSettings {
    
    /**
     * The user's email address
     * @var string
     */
    private $email;
    
    /**
     * The user's ability to upload higher quality images, there will be less compression
     * 
     * @var boolean 
     */
    private $highQuality;
    
    /**
     * Automatically allow all images to be publicly accessible
     * 
     * @var boolean
     */
    private $publicImages;
    
    /**
     * Set the album privacy to this privacy setting on creation
     * 
     * @var string public | hidden | secret
     */
    private $albumPrivacy;
    
    /**
     * False if not a pro user, their expiration date if they are.
     * 
     * @var integer or boolean
     */
    private $proExpiration;
    
    /**
     * True if the user has accepted the terms of uploading to the Imgur gallery.
     * 
     * @var boolean
     */
    private $acceptedGalleryTerms;
    
    /**
     * The email addresses that have been activated to allow uploading
     * 
     * @var string|array
     */
    private $activeEmails;
    
    /**
     * If the user is accepting incoming messages or not
     * 
     * @var boolean
     */
    private $messagingEnabled;
    
    /**
     * An array of users that have been blocked from messaging, the object is blocked_id and blocked_url.
     * 
     * @var \Imgur\Api\Model\Account|array
     */
    private $blockedUsers;

    /**
     * The biography of the user, is displayed in the gallery profile page.
     * 
     * @var string 
     */
    private $bio;
    
    /**
     * Build the AccountSettings object based on an array
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\AccountSettings
     */     
    public function __construct($parameters) {
        if(!empty($parameters['data'])) {
            $parameters = $parameters['data'];
        }
        
        $this->setEmail($parameters['email'])
             ->setHighQuality($parameters['high_quality'])
             ->setPublicImages($parameters['public_images'])
             ->setAlbumPrivacy($parameters['album_privacy'])
             ->setProExpiration($parameters['pro_expiration'])
             ->setAcceptedGalleryTerms($parameters['accepted_gallery_terms'])
             ->setActiveEmails($parameters['active_emails'])
             ->setMessagingEnabled($parameters['messaging_enabled'])
             ->setBlockedUsers($parameters['blocked_users']);
        
        return $this;
    }

    /**
     * The biography of the user, is displayed in the gallery profile page.
     * 
     * @return string
     */
    public function getBio() {
        
        return $this->bio;
    }
    
    /**
     * The biography of the user, is displayed in the gallery profile page.
     * 
     * @param string $bio
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setBio($bio) {
        $this->bio = $bio;
        
        return $this;
    }
    /**
     * The user's email address
     * 
     * @return string
     */
    public function getEmail() {
        
        return $this->email;
    }

    /**
     * The user's email address
     * 
     * @param string $email
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setEmail($email) {
        $this->email = $email;
        
        return $this;
    }

    /**
     * The user's ability to upload higher quality images, there will be less compression
     * 
     * @return boolean
     */
    public function getHighQuality() {
        
        return $this->highQuality;
    }

    /**
     * The user's ability to upload higher quality images, there will be less compression
     * 
     * @param boolean $highQuality
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setHighQuality($highQuality) {
        $this->highQuality = $highQuality;
        
        return $this;
    }

    /**
     * Automatically allow all images to be publicly accessible
     * 
     * @return boolean
     */
    public function getPublicImages() {
        
        return $this->publicImages;
    }

    /**
     * Automatically allow all images to be publicly accessible
     * 
     * @param boolean $publicImages
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setPublicImages($publicImages) {
        $this->publicImages = $publicImages;
        
        return $this;
    }

    /**
     * Set the album privacy to this privacy setting on creation
     * 
     * @return string public | hidden | secret
     */
    public function getAlbumPrivacy() {
        
        return $this->albumPrivacy;
    }

    /**
     * Set the album privacy to this privacy setting on creation
     * 
     * @param string $albumPrivacy public | hidden | secret
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setAlbumPrivacy($albumPrivacy) {
        $this->albumPrivacy = $albumPrivacy;
        
        return $this;
    }

    /**
     * False if not a pro user, their expiration date if they are.
     * 
     * @return integer or boolean
     */
    public function getProExpiration() {
        
        return $this->proExpiration;
    }

    /**
     * False if not a pro user, their expiration date if they are.
     * 
     * @param integer or boolean $proExpiration
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setProExpiration($proExpiration) {
        $this->proExpiration = $proExpiration;
        
        return $this;
    }

    /**
     * True if the user has accepted the terms of uploading to the Imgur gallery.
     * 
     * @return boolean
     */
    public function getAcceptedGalleryTerms() {
        
        return $this->acceptedGalleryTerms;
    }

    /**
     * True if the user has accepted the terms of uploading to the Imgur gallery.
     * 
     * @param boolean $acceptedGalleryTerms
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setAcceptedGalleryTerms($acceptedGalleryTerms) {
        $this->acceptedGalleryTerms = $acceptedGalleryTerms;
        
        return $this;
    }

    /**
     * The email addresses that have been activated to allow uploading
     * 
     * @return string|array
     */
    public function getActiveEmails() {
        
        return $this->activeEmails;
    }

    /**
     * The email addresses that have been activated to allow uploading
     * 
     * @param string|array $activeEmails
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setActiveEmails($activeEmails) {
        $this->activeEmails = $activeEmails;
        
        return $this;
    }

    /**
     * If the user is accepting incoming messages or not
     * 
     * @return boolean
     */
    public function getMessagingEnabled() {
        
        return $this->messagingEnabled;
    }

    /**
     * If the user is accepting incoming messages or not
     * 
     * @param boolean $messagingEnabled
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setMessagingEnabled($messagingEnabled) {
        $this->messagingEnabled = $messagingEnabled;
        
        return $this;
    }

    /**
     * An array of users that have been blocked from messaging, the object is blocked_id and blocked_url.
     * 
     * @return \Imgur\Api\Model\Account|array
     */
    public function getBlockedUsers() {
        
        return $this->blockedUsers;
    }

    /**
     * An array of users that have been blocked from messaging, the object is blocked_id and blocked_url.
     * 
     * @param \Imgur\Api\Model\Account|array $blockedUsers
     * @return \Imgur\Api\Model\AccountSettings
     */
    public function setBlockedUsers($blockedUsers) {
        $this->blockedUsers = $blockedUsers;
        
        return $this;
    }    
}