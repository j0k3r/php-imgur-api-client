## Comment
[Back to the navigation](index.md)

Link: [Imgur Comment API](https://api.imgur.com/endpoints/comment).

#### Comment
```php
<?php
$comment = $client->api('comment')->comment($commentId);
```

#### Comment Creation
```php
<?php
$commentData = array(
                'image_id' => $imageId,
                'comment' => 'Lorem Ipsum Dolor Sit Amet'
);
$basic = $client->api('comment')->create($data);
```

#### Comment Delete
```php
<?php
$basic = $client->api('comment')->deleteComment($commentId);
```

#### Replies
```php
<?php
$replies = $client->api('comment')->replies($commentId);
```

#### Vote
```php
<?php
$basic = $client->api('comment')->vote($commentId, 'up');
```

#### Report
```php
<?php
$basic = $client->api('comment')->report($commentId);
```

#### Reply Creation
```php
<?php
$commentData = array(
                'image_id' => $imageId,
                'comment' => 'Lorem Ipsum Dolor Sit Amet'
);
$basic = $client->api('comment')->createReply($parentCommentId, $commentData);
```