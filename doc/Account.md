## Account
[Back to the navigation](index.md)

Link: [Imgur Account API](https://api.imgur.com/endpoints/account).

#### Account base

```php
$accountBase = $client->api('account')->base();

echo $accountBase->getUrl();
```

#### Account creation. Please check the [Imgur Account Creation](https://api.imgur.com/endpoints/account#account-create) page for the re-captcha key
```php
$account = $client->api('account')->create('username', $recaptchaInformation);
```

#### Account Gallery Favorites
```php
$accountGalleryFavorites = $client->api('account')->galleryFavorites();
```

#### Account Favorites
```php
$accountFavorites = $client->api('account')->favorites();
```

#### Account Submissions
```php
$accountSubmissions = $client->api('account')->submissions();
```

#### Account Settings
```php
$accountSettings = $client->api('account')->settings();
```

#### Change Account Settings
```php
$accountSettings = $client->api('account')->settings();
$accountSettings->setBio('Lorem Ipsum Dolor Sit Amet');
$response = $client->api('account')->changeAccountSettings($accountSettings);
```

#### Account Stats
```php
$accountStats = $client->api('account')->accountStats();
```

#### Account Gallery Profile
```php
$accountGalleryProfile = $client->api('account')->accountGalleryProfile();
```

#### Verify Users E-mail
```php
$basic = $client->api('account')->verifyUsersEmail();
```

#### Send Verification E-mail
```php
$basic = $client->api('account')->sendVerificationEmail();
```

#### Albums
```php
$albums = $client->api('account')->albums();
```

#### Album
```php
$album = $client->api('account')->album($albumId);
```

#### Album IDs
```php
$albumIds = $client->api('account')->albumIds();
```

#### Album Count
```php
$albumCount = $client->api('account')->albumCount();
```

#### Album Delete
```php
$basic = $client->api('account')->albumDelete($albumId);
```

#### Comments
```php
$comments = $client->api('account')->comments();
```

#### Comment
```php
$comment = $client->api('account')->comment($commentId);
```

#### Comment IDs
```php
$commentIds = $client->api('account')->commentIds();
```

#### Comment Count
```php
$commentCount = $client->api('account')->commentCount();
```

#### Comment Delete
```php
$basic = $client->api('account')->commentDelete($commentId);
```

#### Images
```php
$images = $client->api('account')->images();
```

#### Image
```php
$image = $client->api('account')->image($imageId);
```

#### Image Ids
```php
$imageIds = $client->api('account')->imageIds();
```

#### Image Count
```php
$imageCount = $client->api('account')->imageCount();
```

#### Image Deletion
```php
$basic = $client->api('account')->imageDelete($imageDeleteHash);
```

#### Replies
```php
$replies = $client->api('account')->replies();
```