## Gallery
[Back to the navigation](index.md)

Link: [Imgur Gallery API](https://api.imgur.com/endpoints/gallery).

#### Gallery
```php
<?php
$images = $client->api('gallery')->gallery();
```

#### Meme Subgallery
```php
<?php
$images = $client->api('gallery')->memesSubgallery();
```

#### Meme subgallery image
```php
<?php
$images = $client->api('gallery')->memesSubgalleryImage($imageId);
```

#### Sub-reddit Galleries
```php
<?php
$images = $client->api('gallery')->subredditGalleries('pics');
```

#### Sub-reddit Image
```php
<?php
$images = $client->api('gallery')->subredditImage('pics', $imageId);
```

#### Gallery Search
```php
<?php
$images = $client->api('gallery')->search('dog');
```

#### Random Gallery Images
```php
<?php
$images = $client->api('gallery')->randomGalleryImages();
```

#### Submit to Gallery
```php
<?php
$images = $client->api('gallery')->submitToGallery($imageOrAlbumId);
```

#### Remove from Gallery
```php
<?php
$images = $client->api('gallery')->removeFromGallery($imageOrAlbumId);
```

#### Album
```php
<?php
$album = $client->api('gallery')->album($albumId);
```

#### Image
```php
<?php
$image = $client->api('gallery')->image($imageId);
```

#### Report
```php
<?php
$image = $client->api('gallery')->report($imageOrAlbumId);
```

#### Votes
```php
<?php
$votes = $client->api('gallery')->votes($imageOrAlbumId);
```

#### Album/Image Voting
```php
<?php
$vote = $client->api('gallery')->vote($imageOrAlbumId, 'up');
```

#### Comments
```php
$comments = $api->client('gallery')->comments($imageOrAlbumId);
```

#### Comment
```php
<?php
$comments = $api->client('gallery')->comment($imageOrAlbumId, $commentId);
```

#### Album/Image Comment Creation
```php
$commentData = array(
                'comment' => 'Lorem Ipsum Dolor Sit Amet'
            );
$basic = $api->client('gallery')->createComment($imageOrAlbumId, $commentData);
```

#### Album/Image Comment IDs
```php
$commentIds = $api->client('gallery')->commentIds($imageOrAlbumId);
```

#### Album/Image Comment Count
```php
$commentCount = $api->client('gallery')->commentCount($imageOrAlbumId);
```