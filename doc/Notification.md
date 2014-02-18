## Notification
[Back to the navigation](index.md)

Link: [Imgur Notification API](https://api.imgur.com/endpoints/notification).

#### Notifications

```php
<?php
$notifications = $client->api('notification')->getNotifications($onlyNew);
```

#### Notification

```php
<?php
$notification = $client->api('notification')->notification($notificationId);
```

#### Mark notification viewed

```php
<?php
$basic = $client->api('notification')->notificationViewed($notificationId);
```