<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Imgur\Api\Account;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class AccountTest extends ApiTestCase
{
    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Authentication required
     */
    public function testBaseReal()
    {
        $guzzleClient = new GuzzleClient(['base_url' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $account = new Account($client);

        $account->base();
    }

    public function testBaseWithResponse()
    {
        $guzzleClient = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
                'data' => [
                    'id' => 703058,
                    'url' => 'j0k3rx',
                    'bio' => null,
                    'reputation' => 2,
                    'created' => 1327402895,
                    'pro_expiration' => false,
                ],
                'success' => true,
                'status' => 200,
            ]))),
        ]);
        $guzzleClient->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $account = new Account($client);

        $result = $account->base();

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('url', $result);
        $this->assertArrayHasKey('bio', $result);
        $this->assertArrayHasKey('reputation', $result);
        $this->assertArrayHasKey('created', $result);
        $this->assertArrayHasKey('pro_expiration', $result);
    }

    public function testBase()
    {
        $expectedValue = [
            'data' => [
                'id' => 123,
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->base());
    }

    public function testDeleteAccount()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('account/imgur')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->deleteAccount('imgur'));
    }

    public function testGalleryFavorites()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/gallery_favorites/0/newest')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->galleryFavorites());
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testGalleryFavoritesWrongValues()
    {
        $this->getApiMock()->galleryFavorites('me', 0, 'bad sort');
    }

    public function testFavorites()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/favorites')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->favorites());
    }

    public function testSubmissions()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/submissions/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->submissions());
    }

    public function testSettings()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/settings')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->settings());
    }

    public function testChangeAccountSettings()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('account/me/settings')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->changeAccountSettings([
            'show_mature' => true,
        ]));
    }

    public function testAccountStats()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/stats')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->accountStats());
    }

    public function testAccountGalleryProfile()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/gallery_profile')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->accountGalleryProfile());
    }

    public function testVerifyUsersEmail()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/verifyemail')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->verifyUsersEmail());
    }

    public function testSendVerificationEmail()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('account/me/verifyemail')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->sendVerificationEmail());
    }

    public function testAlbums()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/albums/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->albums());
    }

    public function testAlbum()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/album/Arn5NUt')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->album('Arn5NUt'));
    }

    public function testAlbumIds()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/albums/ids/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->albumIds());
    }

    public function testAlbumCount()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/albums/count')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->albumCount());
    }

    public function testAlbumDelete()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('account/me/album/Arn5NUt')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->albumDelete('Arn5NUt'));
    }

    public function testComments()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/comments/newest/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->comments());
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testCommentsWrongValues()
    {
        $this->getApiMock()->comments('me', 0, 'bad sort');
    }

    public function testComment()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => '726305564',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/comment/726305564')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->comment('726305564'));
    }

    public function testCommentIds()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/comments/ids/newest/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->commentIds());
    }

    /**
     * @expectedException \Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testCommentIdsWrongValues()
    {
        $this->getApiMock()->commentIds('me', 0, 'bad sort');
    }

    public function testCommentCount()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/comments/count')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->commentCount());
    }

    public function testCommentDelete()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('account/me/comment/726305564')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->commentDelete('726305564'));
    }

    public function testImages()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/images/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->images());
    }

    public function testImage()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'iCMrM1P',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/image/iCMrM1P')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->image('iCMrM1P'));
    }

    public function testImageIds()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/images/ids/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->imageIds());
    }

    public function testImageCount()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/images/count')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->imageCount());
    }

    public function testImageDelete()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('account/me/image/iCMrM1P')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->imageDelete('iCMrM1P'));
    }

    public function testReplies()
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'Arn5NUt',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/notifications/replies', ['new' => 'false'])
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->replies());
    }

    protected function getApiClass()
    {
        return 'Imgur\Api\Account';
    }
}
