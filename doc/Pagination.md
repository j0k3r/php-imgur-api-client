## Pagination
[Back to the navigation](index.md)

For any API call that supports pagination and is not explicitly available via the method parameters, 
it can be achieved by using the BasicPager object and passing it as the second parameter in the api() call.

```php
<?php
$pager = new \Imgur\Pager\BasicPager(0, 1);
$images = $client->api('account', $pager)->images();
```
