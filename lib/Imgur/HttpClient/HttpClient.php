<?php

namespace Imgur\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Imgur\Middleware\AuthMiddleware;
use Imgur\Middleware\ErrorMiddleware;
use Psr\Http\Message\RequestInterface;

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

    protected $stack;

    /**
     * @param array           $options
     * @param ClientInterface $client
     */
    public function __construct(array $options = [], ClientInterface $client = null)
    {
        $this->options = array_merge($options, $this->options);

        $baseUrl = $this->options['base_url'];
        unset($this->options['base_url']);

        // during test (at least) handler can be injected into the client
        // so we need to retrieve it to be able to inject our own middleware
        $this->stack = HandlerStack::create();
        if (null !== $client) {
            $this->stack = $client->getConfig('handler');
        }

        $this->stack->push(ErrorMiddleware::error());

        $this->client = $client ?: new GuzzleClient([
            'base_uri' => $baseUrl,
            'handler' => $this->stack,
        ]);
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
        $options = [
            'headers' => isset($this->options['headers']) ? $this->options['headers'] : [],
            'body' => isset($this->options['body']) ? $this->options['body'] : '',
        ];

        if (isset($parameters['query'])) {
            $options['query'] = $parameters['query'];
        }

        if ($httpMethod === 'POST' || $httpMethod === 'PUT' || $httpMethod === 'DELETE') {
            $options['form_params'] = $parameters;
        }

        // will throw an Imgur\Exception\ExceptionInterface if sth goes wrong
        return $this->client->request($httpMethod, $url, $options);
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
     * Push authorization middleware.
     *
     * @param array  $token
     * @param string $clientId
     */
    public function addAuthMiddleware($token, $clientId)
    {
        $this->stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($token, $clientId) {
            return (new AuthMiddleware($token, $clientId))->addAuthHeader($request);
        }));
    }
}
