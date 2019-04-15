<?php

namespace Imgur\Tests\HttpClient;

use Guzzle\Http\Message\Request;
use Imgur\Listener\ErrorListener;

class ErrorListenerTest extends \PHPUnit\Framework\TestCase
{
    public function testNothinHappenOnOKResponse()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No user credits available. The limit is 10
     */
    public function testRateLimitUser()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->at(1))
            ->method('getHeader')
            ->with('X-RateLimit-UserRemaining')
            ->will($this->returnValue(0));
        $response->expects($this->at(2))
            ->method('getHeader')
            ->with('X-RateLimit-UserLimit')
            ->will($this->returnValue(10));

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No application credits available. The limit is 10 and will be reset at 2015-09-04
     */
    public function testRateLimitClient()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->at(1))
            ->method('getHeader')
            ->with('X-RateLimit-UserRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(2))
            ->method('getHeader')
            ->with('X-RateLimit-UserLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(3))
            ->method('getHeader')
            ->with('X-RateLimit-ClientRemaining')
            ->will($this->returnValue(0));
        $response->expects($this->at(4))
            ->method('getHeader')
            ->with('X-RateLimit-ClientLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(5))
            ->method('getHeader')
            ->with('X-RateLimit-UserReset')
            ->will($this->returnValue('1441401387')); // 4/9/2015  23:16:27

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Request to: /here failed with: "oops"
     */
    public function testErrorWithJson()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(json_encode(['data' => ['request' => '/here', 'error' => 'oops']])));
        $response->expects($this->at(1))
            ->method('getHeader')
            ->with('X-RateLimit-UserRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(2))
            ->method('getHeader')
            ->with('X-RateLimit-UserLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(3))
            ->method('getHeader')
            ->with('X-RateLimit-ClientRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(4))
            ->method('getHeader')
            ->with('X-RateLimit-ClientLimit')
            ->will($this->returnValue(10));

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Request failed with: "Imgur is temporarily over capacity. Please try again later."
     */
    public function testErrorOverCapacity()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(json_encode(['status' => 500, 'success' => false, 'data' => ['error' => 'Imgur is temporarily over capacity. Please try again later.']])));
        $response->expects($this->at(1))
            ->method('getHeader')
            ->with('X-RateLimit-UserRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(2))
            ->method('getHeader')
            ->with('X-RateLimit-UserLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(3))
            ->method('getHeader')
            ->with('X-RateLimit-ClientRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(4))
            ->method('getHeader')
            ->with('X-RateLimit-ClientLimit')
            ->will($this->returnValue(10));

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Request to: /3/image.json failed with: "You are uploading too fast. Please wait 59 more minutes."
     */
    public function testErrorUploadingTooFast()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(json_encode(['status' => 400, 'success' => false, 'data' => ['error' => ['code' => 429, 'message' => 'You are uploading too fast. Please wait 59 more minutes.', 'type' => 'ImgurException', 'exception' => []], 'request' => '/3/image.json', 'method' => 'POST']])));
        $response->expects($this->at(1))
            ->method('getHeader')
            ->with('X-RateLimit-UserRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(2))
            ->method('getHeader')
            ->with('X-RateLimit-UserLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(3))
            ->method('getHeader')
            ->with('X-RateLimit-ClientRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(4))
            ->method('getHeader')
            ->with('X-RateLimit-ClientLimit')
            ->will($this->returnValue(10));

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    /**
     * @expectedException \Imgur\Exception\ErrorException
     * @expectedExceptionMessage Request to: /3/image.json failed with: "Error code: 666"
     */
    public function testErrorNoMessageInError()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(json_encode(['status' => 400, 'success' => false, 'data' => ['error' => ['code' => 666, 'type' => 'ImgurException', 'exception' => []], 'request' => '/3/image.json', 'method' => 'POST']])));
        $response->expects($this->at(1))
            ->method('getHeader')
            ->with('X-RateLimit-UserRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(2))
            ->method('getHeader')
            ->with('X-RateLimit-UserLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(3))
            ->method('getHeader')
            ->with('X-RateLimit-ClientRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(4))
            ->method('getHeader')
            ->with('X-RateLimit-ClientLimit')
            ->will($this->returnValue(10));

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    /**
     * @expectedException \Imgur\Exception\RuntimeException
     * @expectedExceptionMessage hihi
     */
    public function testErrorWithoutJson()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('hihi'));
        $response->expects($this->at(1))
            ->method('getHeader')
            ->with('X-RateLimit-UserRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(2))
            ->method('getHeader')
            ->with('X-RateLimit-UserLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(3))
            ->method('getHeader')
            ->with('X-RateLimit-ClientRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(4))
            ->method('getHeader')
            ->with('X-RateLimit-ClientLimit')
            ->will($this->returnValue(10));

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    /**
     * @expectedException \Imgur\Exception\RateLimitException
     * @expectedExceptionMessage No post credits available. The limit is 10 and will be reset at 2015-09-04
     */
    public function testRateLimitPost()
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->at(1))
            ->method('getHeader')
            ->with('X-RateLimit-UserRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(2))
            ->method('getHeader')
            ->with('X-RateLimit-UserLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(3))
            ->method('getHeader')
            ->with('X-RateLimit-ClientRemaining')
            ->will($this->returnValue(9));
        $response->expects($this->at(4))
            ->method('getHeader')
            ->with('X-RateLimit-ClientLimit')
            ->will($this->returnValue(10));
        $response->expects($this->at(5))
            ->method('getHeader')
            ->with('X-Post-Rate-Limit-Remaining')
            ->will($this->returnValue(0));
        $response->expects($this->at(6))
            ->method('getHeader')
            ->with('X-Post-Rate-Limit-Limit')
            ->will($this->returnValue(10));
        $response->expects($this->at(7))
            ->method('getHeader')
            ->with('X-Post-Rate-Limit-Reset')
            ->will($this->returnValue('1441401387')); // 4/9/2015  23:16:27

        $listener = new ErrorListener();
        $listener->error($this->getEventMock($response));
    }

    private function getEventMock($response)
    {
        $mock = $this->getMockBuilder('GuzzleHttp\Event\ErrorEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $request = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $mock->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        return $mock;
    }
}
