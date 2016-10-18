<?php

namespace Imgur;

use Imgur\Auth\AuthInterface;
use Imgur\Exception\InvalidArgumentException;
use Imgur\HttpClient\HttpClient;
use Imgur\HttpClient\HttpClientInterface;
use Imgur\Pager\PagerInterface;

/**
 * PHP Imgur API wrapper.
 */
class Client
{
    /**
     * @var array
     */
    private $options = [
        'base_url' => 'https://api.imgur.com/3/',
        'client_id' => null,
        'client_secret' => null,
    ];

    /**
     * The class handling communication with Imgur servers.
     *
     * @var \Imgur\HttpClient\HttpClientInterface
     */
    private $httpClient;

    /**
     * The class handling authentication.
     *
     * @var \Imgur\Auth\AuthInterface
     */
    private $authenticationClient;

    /**
     * Instantiate a new Imgur client.
     *
     * @param null|HttpClientInterface $httpClient Imgur http client
     */
    public function __construct(AuthInterface $authenticationClient = null, HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;
        $this->authenticationClient = $authenticationClient;
    }

    /**
     * @param string                     $name
     * @param Imgur\Pager\PagerInterface $pager
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function api($name, PagerInterface $pager = null)
    {
        if (!$this->getAccessToken()) {
            $this->sign();
        }

        $apiClass = 'Imgur\\Api\\' . ucfirst($name);
        if (class_exists($apiClass)) {
            return new $apiClass($this, $pager);
        }

        throw new InvalidArgumentException('API Method not supported: "' . $name . '" (apiClass: "' . $apiClass . '")');
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->setHttpClient(new HttpClient($this->options));
        }

        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function getOption($name)
    {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        return $this->options[$name];
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @throws InvalidArgumentException
     */
    public function setOption($name, $value)
    {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        $this->options[$name] = $value;
    }

    /**
     * Retrieves the Auth object and also instantiates it if not already present.
     *
     * @return Auth\AuthInterface
     */
    public function getAuthenticationClient()
    {
        if (null === $this->authenticationClient) {
            $this->authenticationClient = new Auth\OAuth2(
                $this->getHttpClient(),
                $this->getOption('client_id'),
                $this->getOption('client_secret')
            );
        }

        return $this->authenticationClient;
    }

    /**
     * Proxy method for the authentication objects URL building method.
     *
     * @param string $responseType
     * @param string $state
     *
     * @return string
     */
    public function getAuthenticationUrl($responseType = 'code', $state = null)
    {
        return $this->getAuthenticationClient()->getAuthenticationUrl($responseType, $state);
    }

    /**
     * Proxy method for exchanging a code for an access token/a pin for an access token.
     *
     * @param string $code
     * @param string $responseType
     *
     * @return string
     */
    public function requestAccessToken($code, $responseType = 'code')
    {
        return $this->getAuthenticationClient()->requestAccessToken($code, $responseType);
    }

    /**
     * Proxy method for retrieving the access token.
     *
     * @return array
     */
    public function getAccessToken()
    {
        return $this->getAuthenticationClient()->getAccessToken();
    }

    /**
     * Proxy method for checking if the access token expired.
     *
     * @return array
     */
    public function checkAccessTokenExpired()
    {
        return $this->getAuthenticationClient()->checkAccessTokenExpired();
    }

    /**
     * Proxy method for refreshing an access token.
     *
     * @return array
     */
    public function refreshToken()
    {
        return $this->getAuthenticationClient()->refreshToken();
    }

    /**
     * Proxy method for setting an access token.
     *
     * @param array $token
     */
    public function setAccessToken($token)
    {
        $this->getAuthenticationClient()->setAccessToken($token);
    }

    /**
     * Proxy method for signing a request.
     */
    public function sign()
    {
        $this->getAuthenticationClient()->sign();
    }
}
