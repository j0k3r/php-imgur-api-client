<?php

namespace Imgur\tests\Api;

use Imgur\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class ApiTestCase extends TestCase
{
    /**
     * @return \Imgur\Api\Account&MockObject
     */
    protected function getApiAccountMock()
    {
        return $this->getApiMock('Imgur\Api\Account');
    }

    /**
     * @return \Imgur\Api\Album&MockObject
     */
    protected function getApiAlbumMock()
    {
        return $this->getApiMock('Imgur\Api\Album');
    }

    /**
     * @return \Imgur\Api\Comment&MockObject
     */
    protected function getApiCommentMock()
    {
        return $this->getApiMock('Imgur\Api\Comment');
    }

    /**
     * @return \Imgur\Api\Conversation&MockObject
     */
    protected function getApiConversationMock()
    {
        return $this->getApiMock('Imgur\Api\Conversation');
    }

    /**
     * @return \Imgur\Api\CustomGallery&MockObject
     */
    protected function getApiCustomGalleryMock()
    {
        return $this->getApiMock('Imgur\Api\CustomGallery');
    }

    /**
     * @return \Imgur\Api\Gallery&MockObject
     */
    protected function getApiGalleryMock()
    {
        return $this->getApiMock('Imgur\Api\Gallery');
    }

    /**
     * @return \Imgur\Api\Image&MockObject
     */
    protected function getApiImageMock()
    {
        return $this->getApiMock('Imgur\Api\Image');
    }

    /**
     * @return \Imgur\Api\Memegen&MockObject
     */
    protected function getApiMemegenMock()
    {
        return $this->getApiMock('Imgur\Api\Memegen');
    }

    /**
     * @return \Imgur\Api\Notification&MockObject
     */
    protected function getApiNotificationMock()
    {
        return $this->getApiMock('Imgur\Api\Notification');
    }

    /**
     * @return \Imgur\Api\Topic&MockObject
     */
    protected function getApiTopicMock()
    {
        return $this->getApiMock('Imgur\Api\Topic');
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return T&MockObject
     */
    private function getApiMock($className): object
    {
        $httpClient = $this->getMockBuilder('Imgur\HttpClient\HttpClient')
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient
            ->expects($this->any())
            ->method('get');
        $httpClient
            ->expects($this->any())
            ->method('delete');
        $httpClient
            ->expects($this->any())
            ->method('post');
        $httpClient
            ->expects($this->any())
            ->method('put');

        $client = new Client(null, $httpClient);

        return $this->getMockBuilder($className)
            ->onlyMethods(['get', 'post', 'put', 'delete'])
            ->setConstructorArgs([$client])
            ->getMock();
    }
}
