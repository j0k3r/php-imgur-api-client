<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\Account;
use Imgur\Client;
use Imgur\Exception\InvalidArgumentException;
use Imgur\HttpClient\HttpClient;

class AccountTest extends ApiTestCase
{
    public function testBaseReal(): void
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Authentication required');

        $httpClient = new HttpClient();
        $client = new Client(null, $httpClient);
        $account = new Account($client);

        $account->base();
    }

    public function testBaseWithResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
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
            ])),
        ]);
        $handler = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $guzzleClient, $handler);
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

    public function testBase(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 123,
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->base());
    }

    public function testDeleteAccount(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('account/imgur')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->deleteAccount('imgur'));
    }

    public function testGalleryFavorites(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/gallery_favorites/0/newest')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->galleryFavorites());
    }

    public function testGalleryFavoritesWrongValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiAccountMock()->galleryFavorites('me', 0, 'bad sort');
    }

    public function testFavorites(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/favorites')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->favorites());
    }

    public function testSubmissions(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/submissions/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->submissions());
    }

    public function testSettings(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/settings')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->settings());
    }

    public function testChangeAccountSettings(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('post')
            ->with('account/me/settings')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->changeAccountSettings([
            'show_mature' => true,
        ]));
    }

    public function testAccountStats(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/stats')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->accountStats());
    }

    public function testAccountGalleryProfile(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/gallery_profile')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->accountGalleryProfile());
    }

    public function testVerifyUsersEmail(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/verifyemail')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->verifyUsersEmail());
    }

    public function testSendVerificationEmail(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('post')
            ->with('account/me/verifyemail')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->sendVerificationEmail());
    }

    public function testAlbums(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/albums/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->albums());
    }

    public function testAlbum(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/album/Arn5NUt')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->album('Arn5NUt'));
    }

    public function testAlbumIds(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/albums/ids/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->albumIds());
    }

    public function testAlbumCount(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/albums/count')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->albumCount());
    }

    public function testAlbumDelete(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('account/me/album/Arn5NUt')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->albumDelete('Arn5NUt'));
    }

    public function testComments(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/comments/newest/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->comments());
    }

    public function testCommentsWrongValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiAccountMock()->comments('me', 0, 'bad sort');
    }

    public function testComment(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/comment/726305564')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->comment('726305564'));
    }

    public function testCommentIds(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/comments/ids/newest/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->commentIds());
    }

    public function testCommentIdsWrongValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiAccountMock()->commentIds('me', 0, 'bad sort');
    }

    public function testCommentCount(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/comments/count')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->commentCount());
    }

    public function testCommentDelete(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('account/me/comment/726305564')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->commentDelete('726305564'));
    }

    public function testImages(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/images/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->images());
    }

    public function testImage(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/image/iCMrM1P')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->image('iCMrM1P'));
    }

    public function testImageIds(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/images/ids/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->imageIds());
    }

    public function testImageCount(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/images/count')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->imageCount());
    }

    public function testImageDelete(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('account/me/image/iCMrM1P')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->imageDelete('iCMrM1P'));
    }

    public function testReplies(): void
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

        $api = $this->getApiAccountMock();
        $api->expects($this->once())
            ->method('get')
            ->with('account/me/notifications/replies', ['new' => 'false'])
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->replies());
    }
}
