<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Accounts
 * 
 * @link https://api.imgur.com/endpoints/account
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Account extends AbstractApi {
    /**
     * Request standard user information.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Account
     */
    public function base($username = 'me') {
        $parameters = $this->get('account/'.$username);
        
        return new Model\Account($parameters);
    }
    
    /**
     * Create a new user on Imgur.  Note: you MUST send recaptcha information with this request. 
     * Use this as the public captcha key: 6LeZbt4SAAAAAG2ccJykgGk_oAqjFgQ1y6daNz-H
     * 
     * @param string $username
     * @param array $recaptchaInformation
     */
    public function create($username, $recaptchaInformation) {
        $parameters = $this->post('account/'.$username, $recaptchaInformation);
        
        return new Model\Account($parameters);
    }
    
    /**
     * Delete a user account, you can only access this if you're logged in as the user.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function delete($username) {
        $parameters = $this->delete('account/'.$username);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Return the images the user has favorited in the gallery.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Image|array
     */
    public function galleryFavorites($username = 'me') {
       $parameters = $this->get('account/'.$username.'/gallery_favorites');
       
       $images = array();
       
       if(empty($parameters['data'])) {
           
           return $images;
       }
       
       foreach($parameters['data'] as $parameter) {
           $images[] = new Model\Image($parameter);
       }
       
       return $images;
    }
    
    /**
     * Returns the users favorited images, only accessible if you're logged in as the user.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum|array 
     */
    public function favorites($username = 'me') {
       $parameters = $this->get('account/'.$username.'/favorites');        

       $images = array();
       
       foreach($parameters['data'] as $parameter) {
           if(!empty($parameter['is_album'])) {
               $images[] = new Model\GalleryAlbum($parameter);
           }
           else {
               $images[] = new Model\GalleryImage($parameter);
           }
       }
       
       return $images;        
    }

    /**
     * Return the images a user has submitted to the gallery
     * 
     * @param string $username
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum|array
     */
    public function submissions($username = 'me') {
       $parameters = $this->get('account/'.$username.'/submissions');        

       $images = array();
       
       foreach($parameters['data'] as $parameter) {
           if(!empty($parameter['is_album'])) {
               $images[] = new Model\GalleryAlbum($parameter);
           }
           else {
               $images[] = new Model\GalleryImage($parameter);
           }
       }
       
       return $images;         
    }
    
    /**
     * Returns the account settings, only accessible if you're logged in as the user
     * 
     * @param string $username
     */
    public function settings($username = 'me') {
       $parameters = $this->get('account/'.$username.'/settings');           

       return new Model\AccountSettings($parameters);
    }
    
    /**
     * Updates the account settings for a given user, the user must be logged in.
     * 
     * @param \Imgur\Api\Model\AccountSettings $account
     * @return \Imgur\Api\Model\Basic
     */
    public function changeAccountSettings(\Imgur\Api\Model\AccountSettings $account) {
        $parameters = array(
            'bio'                    => $account->getBio(),
            'public_images'          => var_export($account->getPublicImages(), true) ,
            'messaging_enabled'      => var_export($account->getMessagingEnabled(), true) ,
            'album_privacy'          => $account->getAlbumPrivacy(),
            'accepted_gallery_terms' => var_export($account->getAcceptedGalleryTerms(), true)
        );
        
        $response = $this->post('account/'.$username.'/submissions', $parameters);    
        
        return new Model\Basic($response);
    }
    
    /**
     * Return the statistics about the account.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\AccountStatistics
     */
    public function accountStats($username = 'me') {
        $parameters = $this->get('account/'.$username.'/stats');
        
        return new Model\AccountStatistics($parameters);
    }
}