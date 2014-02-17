## Account
[Back to the navigation](index.md)

Link: [Imgur Account API](https://api.imgur.com/endpoints/account).

#### Account base

```php
<?php
$accountBase = $client->api('account')->base();

echo $accountBase->getUrl();
```

#### Account creation. Please check the [Imgur Account Creation](https://api.imgur.com/endpoints/account#account-create) page for the re-captcha key
```php
<?php
$account = $client->api('account')->create('username', $recaptchaInformation);
```

#### Account Gallery Favorites
```php
<?php
$accountGalleryFavorites = $client->api('account')->galleryFavorites();
```

#### Account Favorites
```php
<?php
$accountFavorites = $client->api('account')->favorites();
```

#### Account Submissions
```php
<?php
$accountSubmissions = $client->api('account')->submissions();
```

#### Account Settings
```php
<?php
$accountSettings = $client->api('account')->settings();
```

#### Change Account Settings
```php
<?php
$accountSettings = $client->api('account')->settings();
$accountSettings->setBio('Lorem Ipsum Dolor Sit Amet');
$response = $client->api('account')->changeAccountSettings($accountSettings);
```

#### Account Stats
```php
<?php
$accountStats = $client->api('account')->accountStats();
```

#### Account Gallery Profile
```php
<?php
$accountGalleryProfile = $client->api('account')->accountGalleryProfile();
```

#### Verify Users E-mail
```php
<?php
$basic = $client->api('account')->verifyUsersEmail();
```

#### Send Verification E-mail
```php
<?php
$basic = $client->api('account')->sendVerificationEmail();
```

#### Albums
```php
<?php
$albums = $client->api('account')->albums();
```

#### Album
```php
<?php
$album = $client->api('account')->album($albumId);
```

#### Album IDs
```php
<?php
$albumIds = $client->api('account')->albumIds();
```

#### Album Count
```php
<?php
$albumCount = $client->api('account')->albumCount();
```

#### Album Delete
```php
<?php
$basic = $client->api('account')->albumDelete($albumId);
```

#### Comments
```php
<?php
$comments = $client->api('account')->comments();
```

#### Comment
```php
<?php
$comment = $client->api('account')->comment($commentId);
```

#### Comment IDs
```php
<?php
$commentIds = $client->api('account')->commentIds();
```

#### Comment Count
```php
<?php
$commentCount = $client->api('account')->commentCount();
```

#### Comment Delete
```php
<?php
$basic = $client->api('account')->commentDelete($commentId);
```

#### Images
```php
<?php
$images = $client->api('account')->images();
```

#### Image
```php
<?php
$image = $client->api('account')->image($imageId);
```

#### Image Ids
```php
<?php
$imageIds = $client->api('account')->imageIds();
```

#### Image Count
```php
<?php
$imageCount = $client->api('account')->imageCount();
```

#### Image Deletion
```php
<?php
$basic = $client->api('account')->imageDelete($imageDeleteHash);
```

#### Replies
```php
<?php
$replies = $client->api('account')->replies();
```