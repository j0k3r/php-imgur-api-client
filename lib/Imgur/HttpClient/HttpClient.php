<?php

namespace Imgur\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
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
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * HTTP Client Settings.
     *
     * @var array
     */
    protected $options = [
        'base_url' => 'https://api.imgur.com/3/',
    ];

    /**
     * @param array           $options
     * @param ClientInterface $client
     */
    public function __construct(array $options = [], ClientInterface $client = null)
    {
        $this->options = array_merge($options, $this->options);

        $this->client = $client ?: new GuzzleClient(['base_url' => $this->options['base_url']]);

        unset($this->options['base_url']);

        $this->addListener(
            'error',
            [
                new ErrorListener(),
                'error',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get($url, array $parameters = [])
    {
        return $this->performRequest($url, $parameters, 'GET');
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, array $parameters = [])
    {
        return $this->performRequest($url, $parameters, 'DELETE');
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, array $parameters = [])
    {
        return $this->performRequest($url, $parameters, 'POST');
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $parameters = [])
    {
        return $this->performRequest($url, $parameters, 'PUT');
    }

    /**
     * {@inheritdoc}
     */
    public function performRequest($url, $parameters, $httpMethod = 'GET')
    {
        $request = $this->createRequest($url, $parameters, $httpMethod);

        try {
            return $this->client->send($request);
        } catch (\Exception $e) {
            // if there are a previous one it comes from the ErrorListener
            if ($e->getPrevious()) {
                throw $e->getPrevious();
            }

            throw new ErrorException($e->getMessage(), 0, E_ERROR, __FILE__, __LINE__, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest($url, $parameters, $httpMethod = 'GET')
    {
        $options = [
            'headers' => isset($this->options['headers']) ? $this->options['headers'] : [],
            'body' => isset($this->options['body']) ? $this->options['body'] : '',
        ];

        if (isset($parameters['query'])) {
            $options['query'] = $parameters['query'];
        }

        if ($httpMethod === 'POST' || $httpMethod === 'PUT' || $httpMethod === 'DELETE') {
            $options['body'] = $parameters;
        }

        return $this->client->createRequest($httpMethod, $url, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse($response)
    {
        $responseBody = ['data' => [], 'success' => false];

        if ($response) {
            $responseBody = json_decode($response->getBody(), true);
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
