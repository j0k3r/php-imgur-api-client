## Image
[Back to the navigation](index.md)

Link: [Imgur Image API](https://api.imgur.com/endpoints/image).

#### Image Upload
```php
<?php
$imageData = array(
        'image' => $pathToFile,
        'type'  => 'file',
        'name' => 'Lipsum',
        'title' => 'Lorem Ipsum',
        'description' => 'Lorem Ipsum Dolor Sit Amet'
    );

$basic = $client->api('image')->upload($imageData);
```
    
##### OR


```php
<?php
$imageData = array(
        'image' => $urlToFile,
        'album' => $albumId,
        'type'  => 'url',
        'title' => 'Lorem Ipsum',
        'description' => 'Lorem Ipsum Dolor Sit Amet'
    );

$basic = $client->api('image')->upload($imageData);
```
    
##### OR


```php
<?php
$imageData = array(
        'image' => base64_encode(file_get_contents($pathToFile)),
        'album' => $albumId,
        'type'  => 'base64',
        'title' => 'Lorem Ipsum',
        'description' => 'Lorem Ipsum Dolor Sit Amet'
    );

$basic = $client->api('image')->upload($imageData);
```
    
#### Image

```php
<?php
$image = $client->api('image')->image($imageId);
```
    
#### Image Deletion

```php
<?php
$basic = $client->api('image')->deleteImage($imageIdOrDeleteHash);
```
    
#### Update Image Information

```php
<?php
$imageInfo = array(
    'title' => 'Lorem Ipsum',
    'description' => 'Lorem Ipsum Dolor Sit Amet'
);
$basic = $client->api('image')->update($imageIdOrDeleteHash, $imageInfo);
```
    
#### Favorite image

```php
<?php
$basic = $client->api('image')->favorite($imageIdOrDeleteHash);
```