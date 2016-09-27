<?php

namespace Imgur\tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Imgur\Api\Gallery;
use Imgur\Client;
use Imgur\HttpClient\HttpClient;

class GalleryTest extends ApiTestCase
{
    protected function getApiClass()
    {
        return 'Imgur\Api\Gallery';
    }

    /**
     * @expectedException Imgur\Exception\ErrorException
     * @expectedExceptionMessage Authentication required
     */
    public function testBaseReal()
    {
        $guzzleClient = new GuzzleClient(['base_url' => 'https://api.imgur.com/3/']);
        $httpClient = new HttpClient([], $guzzleClient);
        $client = new Client(null, $httpClient);
        $gallery = new Gallery($client);

        $gallery->gallery();
    }

    public function testBaseWithResponse()
    {
        $guzzleClient = new GuzzleClient();
        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], Stream::factory(json_encode([
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
            ]))),
        ]);
        $guzzleClient->getEmitter()->attach($mock);

        $httpClient = new HttpClient([], $guzzleClient);
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

    public function testGallery()
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

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/hot/viral/day/0', ['showViral' => 'true'])
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->gallery());
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testGalleryWrongSortValue()
    {
        $this->getApiMock()->gallery('hot', 'bad sort');
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testGalleryWrongSectionValue()
    {
        $this->getApiMock()->gallery('bad section');
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testGalleryWrongWindowValue()
    {
        $this->getApiMock()->gallery('hot', 'viral', 0, 'bad window');
    }

    public function testMemesSubgallery()
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

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('g/memes/viral/day/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->memesSubgallery());
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testMemesSubgalleryWrongSortValue()
    {
        $this->getApiMock()->memesSubgallery('bad sort');
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testMemesSubgalleryWrongWindowValue()
    {
        $this->getApiMock()->memesSubgallery('viral', 0, 'bad window');
    }

    public function testMemeSubgalleryImage()
    {
        $expectedValue = [
            'data' => [
                'id' => 'MPx6ZXr',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('g/memes/MPx6ZXr')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->memeSubgalleryImage('MPx6ZXr'));
    }

    public function testSubredditGalleries()
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

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/r/pics/time/day/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->subredditGalleries('pics'));
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testSubredditGalleriesWrongSortValue()
    {
        $this->getApiMock()->subredditGalleries('pics', 'bad sort');
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testSubredditGalleriesWrongWindowValue()
    {
        $this->getApiMock()->subredditGalleries('pics', 'time', 0, 'bad window');
    }

    public function testSubredditImage()
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

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/r/pics/yB1PpjL')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->subredditImage('pics', 'yB1PpjL'));
    }

    public function testGalleryTag()
    {
        $expectedValue = [
            'data' => [
                'name' => 'funny',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/t/funny/viral/week/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->galleryTag('funny'));
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testGalleryTagWrongSortValue()
    {
        $this->getApiMock()->galleryTag('funny', 'bad sort');
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testGalleryTagWrongWindowValue()
    {
        $this->getApiMock()->galleryTag('funny', 'time', 0, 'bad window');
    }

    public function testGalleryTagImage()
    {
        $expectedValue = [
            'data' => [
                'id' => 'yB1PpjL',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/t/funny/yB1PpjL')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->galleryTagImage('funny', 'yB1PpjL'));
    }

    public function testGalleryItemTags()
    {
        $expectedValue = [
            'data' => [
                'tags' => [],
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/y1Od4/tags')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->galleryItemTags('y1Od4'));
    }

    public function testGalleryVoteTag()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/y1Od4/vote/tag/funny/up')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->galleryVoteTag('y1Od4', 'funny', 'up'));
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testGalleryVoteTagWrongVoteValue()
    {
        $this->getApiMock()->galleryVoteTag('y1Od4', 'funny', 'bad vote');
    }

    public function testSearch()
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

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/search/time/0', ['q' => '20minutes'])
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->search('20minutes'));
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testSearchWrongValues()
    {
        $this->getApiMock()->search('pics', 'bad sort');
    }

    public function testRandomGalleryImages()
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

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/random/random/0')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->randomGalleryImages());
    }

    public function testSubmitToGallery()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/y1Od4')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->submitToGallery('y1Od4', ['title' => 'yo']));
    }

    /**
     * @expectedException Imgur\Exception\MissingArgumentException
     * @expectedExceptionMessage parameters is missing
     */
    public function testSubmitToGalleryParamMissing()
    {
        $this->getApiMock()->submitToGallery('y1Od4', []);
    }

    public function testRemoveFromGallery()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('gallery/ccWiaRJ')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->removeFromGallery('ccWiaRJ'));
    }

    public function testAlbum()
    {
        $expectedValue = [
            'data' => [
                'id' => 'VOMXz',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/album/VOMXz')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->album('VOMXz'));
    }

    public function testImage()
    {
        $expectedValue = [
            'data' => [
                'id' => 'ccWiaRJ',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/image/ccWiaRJ')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->image('ccWiaRJ'));
    }

    public function testReport()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/VOMXz/report')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->report('VOMXz'));
    }

    public function testVotes()
    {
        $expectedValue = [
            'data' => [
                'ups' => 12,
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/votes')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->votes('VOMXz'));
    }

    public function testVote()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/VOMXz/vote/up')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->vote('VOMXz', 'up'));
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testVoteWrongVoteValue()
    {
        $this->getApiMock()->vote('VOMXz', 'bad vote');
    }

    public function testComments()
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

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/comments/best')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->comments('VOMXz'));
    }

    /**
     * @expectedException Imgur\Exception\InvalidArgumentException
     * @expectedExceptionMessage is wrong. Possible values are
     */
    public function testCommentsWrongValues()
    {
        $this->getApiMock()->comments('VOMXz', 'bad sort');
    }

    public function testComment()
    {
        $expectedValue = [
            'data' => [
                'id' => 'y1Od4',
            ],
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/comment/1234')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->comment('VOMXz', '1234'));
    }

    public function testCreateComment()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/VOMXz/comment')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->createComment('VOMXz', ['comment' => 'yo']));
    }

    /**
     * @expectedException Imgur\Exception\MissingArgumentException
     * @expectedExceptionMessage parameters is missing
     */
    public function testCreateCommentParamMissing()
    {
        $this->getApiMock()->createComment('y1Od4', []);
    }

    public function testCreateReply()
    {
        $expectedValue = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('gallery/VOMXz/comment/123')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->createReply('VOMXz', '123', ['comment' => 'yo']));
    }

    /**
     * @expectedException Imgur\Exception\MissingArgumentException
     * @expectedExceptionMessage parameters is missing
     */
    public function testCreateReplyParamMissing()
    {
        $this->getApiMock()->createReply('y1Od4', '123', []);
    }

    public function testCommentIds()
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

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/comments/ids')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->commentIds('VOMXz'));
    }

    public function testCommentCount()
    {
        $expectedValue = [
            'data' => 21,
            'success' => true,
            'status' => 200,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('gallery/VOMXz/comments/count')
            ->will($this->returnValue($expectedValue));

        $this->assertSame($expectedValue, $api->commentCount('VOMXz'));
    }
}
