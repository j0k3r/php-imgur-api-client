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
    public function deleteAccount($username) {
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
        
        $response = $this->post('account/me/settings', $parameters);    
        
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
    
    /**
     * Returns the totals for the gallery profile.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\GalleryProfile
     */
    public function accountGalleryProfile($username = 'me') {
        $parameters = $this->get('account/'.$username.'/gallery_profile');
        
        if(!empty($parameters['data'])) {
            $parameters = $parameters['data'];
        }
        
        return new Model\GalleryProfile($parameters);
    }
    
    /**
     * Checks to see if user has verified their email address
     * 
     * @param string $username
     * @return boolean
     */
    public function verifyUsersEmail($username = 'me') {
        $parameters = $this->get('account/'.$username.'/verifyemail');

        return new Model\Basic($parameters);
    }
    
    /**
     * Sends an email to the user to verify that their email is valid to upload to gallery. Must be logged in as the user to send.
     * 
     * @param string $username
     */
    public function sendVerificationEmail($username = 'me') {
        $parameters = $this->post('account/'.$username.'/verifyemail');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Get all the albums associated with the account. Must be logged in as the user to see secret and hidden albums.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Album|array
     * @throws \Imgur\Exception\RuntimeException
     */
    public function albums($username = 'me') {
        $parameters = $this->get('account/'.$username.'/albums');
        
        if(empty($parameters['data'])) {
            throw new \Imgur\Exception\RuntimeException('An error occured while attempting to retrieve albums for '.$username.':'.$parameters['error']['message']);
        }
        
        $albums = array();
        
        foreach($parameters['data'] as $parameter) {
            $albums[] = new Model\Album($parameter);
        }
        
        return $albums;
    }
    
    /**
     * Get additional information about an album, this endpoint works the same as the Album Endpoint. 
     * You can also use any of the additional routes that are used on an album in the album endpoint.
     * 
     * @param string $username
     * @param string $albumId
     * @return \Imgur\Api\Model\Album
     */
    public function album($albumId, $username = 'me' ) {
        $parameters = $this->get('account/'.$username.'/album/'.$albumId);

        return new Model\Album($parameters['data']);
    }
    
    /**
     * Return an array of all of the album IDs.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function albumIds($username = 'me') {
        $parameters = $this->get('account/'.$username.'/albums/ids');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Return the total number of albums associated with the account.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function albumCount($username = 'me') {
        $parameters = $this->get('account/'.$username.'/albums/count');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Delete an Album with a given id.
     * 
     * @param string $username
     * @param string $albumId
     * @return \Imgur\Api\Model\Basic
     */
    public function albumDelete($albumId, $username = 'me' ) {
        $parameters = $this->delete('account/'.$username.'/album/'.$albumId);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Return the comments the user has created.
     *
     * @param string $username
     * @return \Imgur\Api\Model\Comment
     */
    public function comments($username = 'me') {
        $parameters = $this->get('account/'.$username.'/comments');
        
        $comments = array();
        
        foreach($parameters['data'] as $parameter) {
            $comments[] = new Model\Comment($parameter);
        }
        
        return $comments;        
    }
    
    /**
     * Return information about a specific comment. This endpoint works the same as the Comment Endpoint. 
     * You can use any of the additional actions that the comment endpoint allows on this end point.
     * 
     * @param string $commentId
     * @param string $username
     * @return \Imgur\Api\Model\Comment
     */
    public function comment($commentId, $username = 'me') {
        $parameters = $this->get('account/'.$username.'/comment/'.$commentId);
        
        return new Model\Comment($parameters['data']);
    }
    
    /**
     * Return an array of all of the comment IDs.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function commentIds($username = 'me') {
        $parameters = $this->get('account/'.$username.'/comments/ids');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Return a count of all of the comments associated with the account.
     * 
     * @param type $username
     * @return \Imgur\Api\Model\Basic
     */
    public function commentCount($username = 'me') {
        $parameters = $this->get('account/'.$username.'/comments/count');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Delete a comment. You are required to be logged in as the user whom created the comment.
     * 
     * @param string $commentId
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function commentDelete($commentId, $username = 'me') {
        $parameters = $this->delete('account/'.$username.'/comment/'.$commentId);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Return all of the images associated with the account. 
     * You can page through the images by setting the page, this defaults to 0.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Image
     */
    public function images($username = 'me') {
        $parameters = $this->get('account/'.$username.'/images');
        
        $images = array();
        
        foreach($parameters['data'] as $parameter) {
            $images[] = new Model\Image($parameter);
        }
        
        return $images;
    }
    
    /**
     * Return information about a specific image. 
     * This endpoint works the same as the Image Endpoint. You can use any of the additional actions that the image endpoint with this endpoint.
     * 
     * @param string $imageId
     * @param string $username
     * @return \Imgur\Api\Model\Image
     */
    public function image($imageId, $username = 'me') {
        $parameters = $this->get('account/'.$username.'/image/'.$imageId);
        
        return new Model\Image($parameters['data']);
    }
    
    /**
     * Returns an array of Image IDs that are associated with the account.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function imageIds($username = 'me') {
        $parameters = $this->get('account/'.$username.'/images/ids');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Returns the total number of images associated with the account.
     * 
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function imageCount($username = 'me') {
        $parameters = $this->get('account/'.$username.'/images/count');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Deletes an Image. This requires a delete hash rather than an ID.
     * 
     * @param string $deleteHash
     * @param string $username
     * @return \Imgur\Api\Model\Basic
     */
    public function imageDelete($deleteHash, $username = 'me') {
        $parameters = $this->delete('account/'.$username.'/image/'.$deleteHash);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Returns all of the reply notifications for the user. Required to be logged in as that user
     * 
     * @param string $username
     * @param boolean $onlyNew
     * @return \Imgur\Api\Model\Notification
     */
    public function replies($username = 'me', $onlyNew = false) {
        $parameters = $this->get('account/'.$username.'/notifications/replies?new='.var_export($onlyNew, true));
        
        $replies = array();
        
        foreach($parameters['data'] as $parameter) {
            $reply = new Model\Comment($parameter['content']);
            
            $replies[] = new Model\Notification($parameter, $reply);
        }
        
        return $replies;
    }
}