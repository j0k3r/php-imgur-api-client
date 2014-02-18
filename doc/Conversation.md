## Conversation
[Back to the navigation](index.md)

Link: [Imgur Conversation API](https://api.imgur.com/endpoints/conversation).


#### Conversation list

```php
<?php
$conversations = $client->api('conversation')->conversationList();
```

#### Conversation

```php
<?php
$conversation = $client->api('conversation')->conversation($conversationId);
```

#### Message creation

```php
<?php 
$messageData = array(
    'recipient' => $recipientName,
    'body' => 'Lorem Ipsum'
);
$basic = $client->api('conversation')->messageCreate($messageData);
```

#### Conversation deletion

```php
<?php
$basic = $client->api('conversation')->conversationDelete($conversationId);
```

#### Report sender

```php
<?php
$basic = $client->api('conversation')->reportSender($senderUsername);
```

#### Block sender

```php
<?php
$basic = $client->api('conversation')->blockSender($senderUsername);
```