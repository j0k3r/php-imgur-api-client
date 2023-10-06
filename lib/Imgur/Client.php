<?php

namespace Imgur;

use Imgur\Api\AbstractApi;
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
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * The class handling authentication.
     *
     * @var AuthInterface
     */
    private $authenticationClient;

    /**
     * Instantiate a new Imgur client.
     *
     * @param HttpClientInterface|null $httpClient Imgur http client
     */
    public function __construct(AuthInterface $authenticationClient = null, HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;
        $this->authenticationClient = $authenticationClient;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function api(string $name, PagerInterface $pager = null): AbstractApi
    {
        if (!$this->getAccessToken()) {
            $this->sign();
        }

        $apiClass = 'Imgur\\Api\\' . ucfirst($name);
        if (class_exists($apiClass)) {
            /** @var AbstractApi */
            $api = new $apiClass($this, $pager);

            return $api;
        }

        throw new InvalidArgumentException('API Method not supported: "' . $name . '" (apiClass: "' . $apiClass . '")');
    }

    public function getHttpClient(): HttpClientInterface
    {
        if (null === $this->httpClient) {
            $this->setHttpClient(new HttpClient($this->options));
        }

        return $this->httpClient;
    }

    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getOption(string $name): ?string
    {
        if (!\array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        return $this->options[$name];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setOption(string $name, string $value = null): void
    {
        if (!\array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        $this->options[$name] = $value;
    }

    /**
     * Retrieves the Auth object and also instantiates it if not already present.
     */
    public function getAuthenticationClient(): AuthInterface
    {
        if (null === $this->authenticationClient) {
            $this->authenticationClient = new Auth\OAuth2(
                $this->getHttpClient(),
                (string) $this->getOption('client_id'),
                (string) $this->getOption('client_secret')
            );
        }

        return $this->authenticationClient;
    }

    /**
     * Proxy method for the authentication objects URL building method.
     */
    public function getAuthenticationUrl(string $responseType = 'code', string $state = null): string
    {
        return $this->getAuthenticationClient()->getAuthenticationUrl($responseType, $state);
    }

    /**
     * Proxy method for exchanging a code for an access token/a pin for an access token.
     */
    public function requestAccessToken(string $code, string $responseType = 'code'): array
    {
        return $this->getAuthenticationClient()->requestAccessToken($code, $responseType);
    }

    /**
     * Proxy method for retrieving the access token.
     */
    public function getAccessToken(): ?array
    {
        return $this->getAuthenticationClient()->getAccessToken();
    }

    /**
     * Proxy method for checking if the access token expired.
     */
    public function checkAccessTokenExpired(): bool
    {
        return $this->getAuthenticationClient()->checkAccessTokenExpired();
    }

    /**
     * Proxy method for refreshing an access token.
     */
    public function refreshToken(): array
    {
        return $this->getAuthenticationClient()->refreshToken();
    }

    /**
     * Proxy method for setting an access token.
     */
    public function setAccessToken(array $token): void
    {
        $this->getAuthenticationClient()->setAccessToken($token);
    }

    /**
     * Proxy method for signing a request.
     */
    public function sign(): void
    {
        $this->getAuthenticationClient()->sign();
    }
}
