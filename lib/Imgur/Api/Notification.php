<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Notifications
 * 
 * @link https://api.imgur.com/endpoints/notification
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Notification extends AbstractApi {
    
    /**
     * Get all notifications for the user that's currently logged in
     * 
     * @param boolean $new
     * @return \Imgur\Api\Model\Notification|array
     */
    public function getNotifications($new = true) {
        $parameters = $this->get('notification?new='.var_export($new, true));
        
        $notifications = array();
        
        foreach($parameters['data'] as $parameter) {
            $notifications[] = new Model\Notification($parameter);
        }
        
        return $notifications;
    }
    
    /**
     * Returns the data about a specific notification
     * 
     * @param string $notificationId
     * @return \Imgur\Api\Model\Notification
     */
    public function notification($notificationId) {
        $parameters = $this->get('notification/'.$notificationId);
        
        return new Model\Notification($parameters);
    }
    
    /**
     * Marks a notification as viewed, this way it no longer shows up in the basic notification request
     * 
     * @param string $notificationId
     * @return \Imgur\Api\Model\Basic
     */
    public function notificationViewed($notificationId) {
        $parameters = $this->post('notification/'.$notificationId);
        
        return new Model\Basic($parameters);
    }
}