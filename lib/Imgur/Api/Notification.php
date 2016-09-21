<?php

namespace Imgur\Api;

/**
 * CRUD for Notifications.
 *
 * @link https://api.imgur.com/endpoints/notification
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Notification extends AbstractApi
{
    /**
     * Get all notifications for the user that's currently logged in.
     *
     * @param string $new false for all notifications, true for only non-viewed notification
     *
     * @link https://api.imgur.com/endpoints/notification#notifications
     *
     * @return array With keys "replies" & "messages"
     */
    public function notifications($new = true)
    {
        $new = $new ? 'true' : 'false';

        return $this->get('notification', ['new' => $new]);
    }

    /**
     * Returns the data about a specific notification.
     *
     * @param string $notificationId
     *
     * @link https://api.imgur.com/endpoints/notification#notification
     *
     * @return array (@see https://api.imgur.com/models/notification)
     */
    public function notification($notificationId)
    {
        return $this->get('notification/' . $notificationId);
    }

    /**
     * Marks a notification as viewed, this way it no longer shows up in the basic notification request.
     *
     * @param string $notificationId
     *
     * @link https://api.imgur.com/endpoints/notification#notification-viewed
     *
     * @return bool
     */
    public function notificationViewed($notificationId)
    {
        return $this->post('notification/' . $notificationId);
    }
}
