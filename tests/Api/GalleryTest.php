<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Imgur\Api\Gallery;
use Imgur\Client;
use Imgur\Exception\InvalidArgumentException;
use Imgur\HttpClient\HttpClient;

class GalleryTest extends ApiTestCase
{
    public function testBaseReal(): void
    {
        $this->expectException(\Imgur\Exception\ErrorException::class);
        $this->expectExceptionMessage('Authentication required');

        $httpClient = new HttpClient();
        $client = new Client(null, $httpClient);
        $gallery = new Gallery($client);

        $gallery->gallery();
    }

    public function testBaseWithResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'data' => [
                    [
                        'id' => 'y1Od4',
                        'title' => 'My Reaction When I realize I forgot to change modes on my calculator after a trig test',
                        'description' => null,
                        'datetime' => 1474412509,
                        'cover' => 'MyvCuoP',
                        'cover_width' => 451,
                        'cover_height' => 379,
                        'account_url' => 'REACTIONGIFMASTER12000',
                        'account_id' => 40210864,
                        'privacy' => 'public',
                        'layout' => 'blog',
                        'views' => 244204,
                        'link' => 'http://imgur.com/a/y1Od4',
                        'ups' => 11965,
                        'downs' => 188,
                        'points' => 11777,
                        'score' => 11777,
                        'is_album' => true,
                        'vote' => null,
                        'favorite' => false,
                        'nsfw' => false,
                        'section' => '',
                        'comment_count' => 240,
                        'topic' => 'No Topic',
                        'topic_id' => 29,
                        'images_count' => 1,
                        'in_gallery' => true,
                        'is_ad' => false,
                    ],
                ],
                'success' => true,
                'status' => 200,
            ])),
        ]);
        $handler = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handler]);

        $httpClient = new HttpClient([], $guzzleClient, $handler);
        $client = new Client(null, $httpClient);
        $gallery = new Gallery($client);

        $result = $gallery->gallery();

        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('title', $result[0]);
        $this->assertArrayHasKey('description', $result[0]);
        $this->assertArrayHasKey('datetime', $result[0]);
        $this->assertArrayHasKey('cover', $result[0]);
        $this->assertArrayHasKey('cover_width', $result[0]);
        $this->assertArrayHasKey('cover_height', $result[0]);
        $this->assertArrayHasKey('account_url', $result[0]);
        $this->assertArrayHasKey('account_id', $result[0]);
        $this->assertArrayHasKey('privacy', $result[0]);
        $this->assertArrayHasKey('layout', $result[0]);
        $this->assertArrayHasKey('views', $result[0]);
        $this->assertArrayHasKey('link', $result[0]);
        $this->assertArrayHasKey('ups', $result[0]);
        $this->assertArrayHasKey('downs', $result[0]);
        $this->assertArrayHasKey('points', $result[0]);
        $this->assertArrayHasKey('score', $result[0]);
        $this->assertArrayHasKey('is_album', $result[0]);
        $this->assertArrayHasKey('vote', $result[0]);
        $this->assertArrayHasKey('favorite', $result[0]);
        $this->assertArrayHasKey('nsfw', $result[0]);
        $this->assertArrayHasKey('section', $result[0]);
        $this->assertArrayHasKey('comment_count', $result[0]);
        $this->assertArrayHasKey('topic', $result[0]);
        $this->assertArrayHasKey('topic_id', $result[0]);
        $this->assertArrayHasKey('images_count', $result[0]);
        $this->assertArrayHasKey('in_gallery', $result[0]);
        $this->assertArrayHasKey('is_ad', $result[0]);
    }

    public function testGallery(): void
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'y1Od4',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/hot/viral/day/0', ['showViral' => 'true'])
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->gallery());
    }

    public function testGalleryWrongSortValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->gallery('hot', 'bad sort');
    }

    public function testGalleryWrongSectionValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->gallery('bad section');
    }

    public function testGalleryWrongWindowValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->gallery('hot', 'viral', 0, 'bad window');
    }

    public function testMemesSubgallery(): void
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'y1Od4',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('g/memes/viral/day/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->memesSubgallery());
    }

    public function testMemesSubgalleryWrongSortValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->memesSubgallery('bad sort');
    }

    public function testMemesSubgalleryWrongWindowValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->memesSubgallery('viral', 0, 'bad window');
    }

    public function testMemeSubgalleryImage(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'MPx6ZXr',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('g/memes/MPx6ZXr')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->memeSubgalleryImage('MPx6ZXr'));
    }

    public function testSubredditGalleries(): void
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'y1Od4',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/r/pics/time/day/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->subredditGalleries('pics'));
    }

    public function testSubredditGalleriesWrongSortValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->subredditGalleries('pics', 'bad sort');
    }

    public function testSubredditGalleriesWrongWindowValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->subredditGalleries('pics', 'time', 0, 'bad window');
    }

    public function testSubredditImage(): void
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'y1Od4',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/r/pics/yB1PpjL')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->subredditImage('pics', 'yB1PpjL'));
    }

    public function testGalleryTag(): void
    {
        $expectedValue = [
            'data' => [
                'name' => 'funny',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/t/funny/viral/week/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->galleryTag('funny'));
    }

    public function testGalleryTagWrongSortValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->galleryTag('funny', 'bad sort');
    }

    public function testGalleryTagWrongWindowValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->galleryTag('funny', 'time', 0, 'bad window');
    }

    public function testGalleryTagImage(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'yB1PpjL',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/t/funny/yB1PpjL')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->galleryTagImage('funny', 'yB1PpjL'));
    }

    public function testGalleryItemTags(): void
    {
        $expectedValue = [
            'data' => [
                'tags' => [],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/y1Od4/tags')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->galleryItemTags('y1Od4'));
    }

    public function testGalleryVoteTag(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/y1Od4/vote/tag/funny/up')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->galleryVoteTag('y1Od4', 'funny', 'up'));
    }

    public function testGalleryVoteTagWrongVoteValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->galleryVoteTag('y1Od4', 'funny', 'bad vote');
    }

    public function testSearch(): void
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'ccWiaRJ',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/search/time/0', ['q' => '20minutes'])
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->search('20minutes'));
    }

    public function testSearchWrongValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->search('pics', 'bad sort');
    }

    public function testRandomGalleryImages(): void
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'y1Od4',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/random/random/0')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->randomGalleryImages());
    }

    public function testSubmitToGallery(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/y1Od4')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->submitToGallery('y1Od4', ['title' => 'yo']));
    }

    public function testSubmitToGalleryParamMissing(): void
    {
        $this->expectException(\Imgur\Exception\MissingArgumentException::class);
        $this->expectExceptionMessage('parameters is missing');

        $this->getApiGalleryMock()->submitToGallery('y1Od4', []);
    }

    public function testRemoveFromGallery(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('gallery/ccWiaRJ')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->removeFromGallery('ccWiaRJ'));
    }

    public function testAlbum(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'VOMXz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/album/VOMXz')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->album('VOMXz'));
    }

    public function testImage(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'ccWiaRJ',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/image/ccWiaRJ')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->image('ccWiaRJ'));
    }

    public function testReport(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/VOMXz/report')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->report('VOMXz'));
    }

    public function testVotes(): void
    {
        $expectedValue = [
            'data' => [
                'ups' => 12,
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/votes')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->votes('VOMXz'));
    }

    public function testVote(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/VOMXz/vote/up')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->vote('VOMXz', 'up'));
    }

    public function testVoteWrongVoteValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->vote('VOMXz', 'bad vote');
    }

    public function testComments(): void
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'y1Od4',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/comments/best')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->comments('VOMXz'));
    }

    public function testCommentsWrongValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is wrong. Possible values are');

        $this->getApiGalleryMock()->comments('VOMXz', 'bad sort');
    }

    public function testComment(): void
    {
        $expectedValue = [
            'data' => [
                'id' => 'y1Od4',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/comment/1234')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->comment('VOMXz', '1234'));
    }

    public function testCreateComment(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/VOMXz/comment')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->createComment('VOMXz', ['comment' => 'yo']));
    }

    public function testCreateCommentParamMissing(): void
    {
        $this->expectException(\Imgur\Exception\MissingArgumentException::class);
        $this->expectExceptionMessage('parameters is missing');

        $this->getApiGalleryMock()->createComment('y1Od4', []);
    }

    public function testCreateReply(): void
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/VOMXz/comment/123')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->createReply('VOMXz', '123', ['comment' => 'yo']));
    }

    public function testCreateReplyParamMissing(): void
    {
        $this->expectException(\Imgur\Exception\MissingArgumentException::class);
        $this->expectExceptionMessage('parameters is missing');

        $this->getApiGalleryMock()->createReply('y1Od4', '123', []);
    }

    public function testCommentIds(): void
    {
        $expectedValue = [
            'data' => [
                [
                    'id' => 'y1Od4',
                ],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/comments/ids')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->commentIds('VOMXz'));
    }

    public function testCommentCount(): void
    {
        $expectedValue = [
            'data' => 21,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiGalleryMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/comments/count')
            ->willReturn($expectedValue);

        $this->assertSame($expectedValue, $api->commentCount('VOMXz'));
    }
}
