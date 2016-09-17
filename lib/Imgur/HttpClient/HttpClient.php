<?php

namespace Imgur\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use Imgur\Exception\ErrorException;
use Imgur\Exception\RuntimeException;
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
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * HTTP Client Settings.
     *
     * @var array
     */
    protected $options = array(
        'base_url' => 'https://api.imgur.com/3/',
    );

    /**
     * @param array           $options
     * @param ClientInterface $client
     */
    public function __construct(array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge($options, $this->options);

        $this->client = $client ?: new GuzzleClient(array('base_url' => $this->options['base_url']));

        unset($this->options['base_url']);

        $this->addListener(
            'error',
            array(
                new ErrorListener(),
                'error',
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
        $options = array(
            'query' => $parameters,
            'headers' => isset($this->options['headers']) ? $this->options['headers'] : array(),
            'body' => isset($this->options['body']) ? $this->options['body'] : '',
        );

        if ($httpMethod === 'POST' || $httpMethod === 'DELETE') {
            $options['body'] = $parameters;
        }

        return $this->client->createRequest($httpMethod, $url, $options);
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

        return $responseBody['data'];
    }

    /**
     * Attaches a listener to a HttpClient event.
     *
     * @param string $eventName
     * @param array  $listener
     */
    public function addListener($eventName, $listener)
    {
        $this
            ->client
            ->getEmitter()
            ->on($eventName, $listener);
    }
}
