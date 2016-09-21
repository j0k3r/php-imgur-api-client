<?php

namespace Imgur\Tests\HttpClient;

use GuzzleHttp\Message\Request;
use Imgur\Client;
use Imgur\Listener\AuthListener;

class AuthListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testDefineClientIdOnBadToken()
    {
        $request = $this->createMock('GuzzleHttp\Message\RequestInterface');
        $request->expects($this->once())
            ->method('setHeader')
            ->with('Authorization');

        $listener = new AuthListener('token', 'clientid');
        $listener->before($this->getEventMock($request));
    }

    public function testDefineBeareOnGoodToken()
    {
        $request = $this->createMock('GuzzleHttp\Message\RequestInterface');
        $request->expects($this->once())
            ->method('setHeader')
            ->with('Authorization');

        $listener = new AuthListener(['access_token' => 'T0K3N'], 'clientid');
        $listener->before($this->getEventMock($request));
    }

    public function testSendClientIdOnBadToken()
    {
        $request = new Request('GET', '/res');

        $listener = new AuthListener('token', 'clientid');
        $listener->before($this->getEventMock($request));

        $this->assertSame('Client-ID clientid', $request->getHeader('Authorization'));
    }

    public function testSendBeareOnGoodToken()
    {
        $request = new Request('GET', '/res');

        $listener = new AuthListener(['access_token' => 'T0K3N'], 'clientid');
        $listener->before($this->getEventMock($request));

        $this->assertSame('Bearer T0K3N', $request->getHeader('Authorization'));
    }

    private function getEventMock($request = null)
    {
        $mock = $this->getMockBuilder('GuzzleHttp\Event\BeforeEvent')
            ->disableOriginalConstructor()
            ->getMock();

        if ($request) {
            $mock->expects($this->any())
                ->method('getRequest')
                ->will($this->returnValue($request));
        }

        return $mock;
    }
}
