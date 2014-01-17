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
     * @return array of \Imgur\Api\Model\Image
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
    
    public function favorites($username = 'me') {
       $parameters = $this->get('account/'.$username.'/favorites');        
       echo '<pre>';
       var_dump($parameters);die();
       $images = array();
       
       if(empty($parameters['data'])) {
           
           return $images;
       }
       
       foreach($parameters['data'] as $parameter) {
           $images[] = new Model\GalleryImage($parameter);
       }
       
       return $images;        
    }
}