<?php

namespace Imgur\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Imgur\Middleware\AuthMiddleware;
use Imgur\Middleware\ErrorMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @var ClientInterface
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

    /** @var HandlerStack */
    protected $stack;

    public function __construct(array $options = [], ClientInterface $client = null, HandlerStack $stack = null)
    {
        $this->options = array_merge($options, $this->options);

        $baseUrl = $this->options['base_url'];
        unset($this->options['base_url']);

        // during test (at least) handler can be injected into the client
        // so we need to retrieve it to be able to inject our own middleware
        $this->stack = (null !== $client && null !== $stack) ? $stack : HandlerStack::create();

        $this->stack->push(ErrorMiddleware::error());

        $this->client = $client ?: new GuzzleClient([
            'base_uri' => $baseUrl,
            'handler' => $this->stack,
        ]);
    }

    public function get($url, array $parameters = []): ResponseInterface
    {
        return $this->performRequest($url, $parameters, 'GET');
    }

    public function delete($url, array $parameters = []): ResponseInterface
    {
        return $this->performRequest($url, $parameters, 'DELETE');
    }

    public function post($url, array $parameters = []): ResponseInterface
    {
        return $this->performRequest($url, $parameters, 'POST');
    }

    public function put($url, array $parameters = []): ResponseInterface
    {
        return $this->performRequest($url, $parameters, 'PUT');
    }

    public function performRequest($url, $parameters, $httpMethod = 'GET'): ResponseInterface
    {
        $options = [
            'headers' => isset($this->options['headers']) ? $this->options['headers'] : [],
            'body' => isset($this->options['body']) ? $this->options['body'] : '',
        ];

        if (isset($parameters['query'])) {
            $options['query'] = $parameters['query'];
            unset($parameters['query']);
        }

        if ('POST' === $httpMethod || 'PUT' === $httpMethod || 'DELETE' === $httpMethod) {
            if ('POST' === $httpMethod && isset($parameters['type']) && 'file' === $parameters['type']) {
                foreach ($parameters as $key => $value) {
                    $options['multipart'][] = ['name' => $key, 'contents' => $value];
                }
            } else {
                $options['form_params'] = $parameters;
            }
        }

        // will throw an Imgur\Exception\ExceptionInterface if sth goes wrong
        return $this->client->request($httpMethod, $url, $options);
    }

    public function parseResponse(ResponseInterface $response): array
    {
        $responseBody = [
            'data' => [],
            'success' => false,
        ];

        if ((string) $response->getBody()) {
            $responseBody = json_decode($response->getBody(), true);
        }

        return $responseBody['data'];
    }

    public function addAuthMiddleware($token, string $clientId): void
    {
        $this->stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($token, $clientId) {
            return (new AuthMiddleware($token, $clientId))->addAuthHeader($request);
        }));
    }
}
