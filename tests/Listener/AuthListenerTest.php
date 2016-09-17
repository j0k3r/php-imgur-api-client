<?php

namespace Imgur\Tests\HttpClient;

use Guzzle\Http\Message\Request;
use Imgur\Client;
use Imgur\Listener\AuthListener;

class AuthListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testDefineClientIdOnBadToken()
    {
        $request = $this->createMock('Guzzle\Http\Message\RequestInterface');
        $request->expects($this->once())
            ->method('setHeader')
            ->with('Authorization');

        $listener = new AuthListener('token', 'clientid');
        $listener->onRequestBeforeSend($this->getEventMock($request));
    }

    public function testDefineBeareOnGoodToken()
    {
        $request = $this->createMock('Guzzle\Http\Message\RequestInterface');
        $request->expects($this->once())
            ->method('setHeader')
            ->with('Authorization');

        $listener = new AuthListener(array('access_token' => 'T0K3N'), 'clientid');
        $listener->onRequestBeforeSend($this->getEventMock($request));
    }

    public function testSendClientIdOnBadToken()
    {
        $request = new Request('GET', '/res');

        $listener = new AuthListener('token', 'clientid');
        $listener->onRequestBeforeSend($this->getEventMock($request));

        $this->assertEquals('Client-ID clientid', $request->getHeader('Authorization'));
    }

    public function testSendBeareOnGoodToken()
    {
        $request = new Request('GET', '/res');

        $listener = new AuthListener(array('access_token' => 'T0K3N'), 'clientid');
        $listener->onRequestBeforeSend($this->getEventMock($request));

        $this->assertEquals('Bearer T0K3N', $request->getHeader('Authorization'));
    }

    private function getEventMock($request = null)
    {
        $mock = $this->getMockBuilder('Guzzle\Common\Event')->getMock();

        if ($request) {
            $mock->expects($this->any())
                ->method('offsetGet')
                ->will($this->returnValue($request));
        }

        return $mock;
    }
}
