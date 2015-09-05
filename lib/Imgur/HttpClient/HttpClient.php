<?php

namespace Imgur\HttpClient;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\Request;
use Imgur\Exception\RuntimeException;
use Imgur\Exception\ErrorException;
use Imgur\Listener\ErrorListener;

/**
 * Basic client for performing HTTP requests.
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class HttpClient implements HttpClientInterface
{
    /**
     * The Guzzle instance.
     *
     * @var Guzzle\Http\Client
     */
    protected $client;

    /**
     * HTTP Client Settings.
     *
     * @var array
     */
    protected $options = array(
        'base_url' => 'https://api.imgur.com/3/',
        'headers' => array(),
        'body' => array(),
    );

    /**
     * @param array                        $options
     * @param \Guzzle\Http\ClientInterface $client
     */
    public function __construct(array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge_recursive($options, $this->options);
        $this->client = $client ?: new GuzzleClient($this->options['base_url']);

        $this->addListener(
            'request.error',
            array(
                new ErrorListener(),
                'onRequestError',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get($url, array $parameters = array())
    {
        return $this->performRequest($url, $parameters, 'GET');
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, array $parameters = array())
    {
        return $this->performRequest($url, $parameters, 'DELETE');
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, array $parameters = array())
    {
        return $this->performRequest($url, $parameters, 'POST');
    }

    /**
     * {@inheritdoc}
     */
    public function performRequest($url, $parameters, $httpMethod = 'GET')
    {
        $request = $this->createRequest($url, $parameters, $httpMethod);

        try {
            return $this->client->send($request);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), null, null, null, $e);
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest($url, $parameters, $httpMethod = 'GET')
    {
        if ($httpMethod == 'POST' || $httpMethod == 'DELETE') {
            return $this->client->createRequest($httpMethod, $url, $this->options['headers'], $parameters);
        }

        return $this->client->createRequest($httpMethod, $url, $this->options['headers'], $this->options['body'], $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse($response)
    {
        $responseBody = array('data' => array(), 'success' => false);

        if ($response) {
            $responseBody = json_decode($response->getBody(true), true);
        }

        return $responseBody;
    }

    /**
     * Attaches a listener to a HttpClient event.
     *
     * @param string $eventName
     * @param array  $listener
     */
    public function addListener($eventName, $listener)
    {
        $this->client->getEventDispatcher()->addListener($eventName, $listener);
    }
}
