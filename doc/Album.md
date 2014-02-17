## Album
[Back to the navigation](index.md)

Link: [Imgur Album API](https://api.imgur.com/endpoints/album).

#### Album info
```php
<?php
$album = $client->api('album')->album($albumId);
```

#### Album Images
```php
$albumImages = $client->api('album')->albumImages($albumId);
```

#### Album Image
```php
$albumImage = $client->api('album')->albumImage($albumId, $imageId);
```

#### Album Creation
```php
$albumDetails = array(
                    'ids' => array($imageId1, $imageId2),
                    'title' => 'Lorem Ipsum',
                    'description' => 'Lorem Ipsum Dolor Sit Amet'
);
$basic = $client->api('album')->create($albumDetails);
```

#### Update Album
```php
$albumDetails = array(
                    'ids' => array($imageId1, $imageId2),
                    'title' => 'Lorem Ipsum',
                    'description' => 'Lorem Ipsum Dolor Sit Amet'
);
$basic = $client->api('album')->update($albumIdOrDeleteHash, $albumDetails);
```

#### Album Deletion
```php
$basic = $client->api('album')->deleteAlbum($albumIdOrDeleteHash);
```

#### Favorite Album
```php
$basic = $client->api('album')->favoriteAlbum($albumId);
```

#### Set Album Images
```php
$imageIds = array($imageId1, $imageId2);
$basic = $client->api('album')->setAlbumImages($albumId, $imageIds);
```

#### Add Images to an Album
```php
$imageIds = array($imageId1, $imageId2);
$basic = $client->api('album')->addImages($albumId, $imageIds);
```

#### Remove Images
```php
$imageIds = array($imageId1, $imageId2);
$basic = $client->api('album')->removeImages($albumId, $imageIds);
```