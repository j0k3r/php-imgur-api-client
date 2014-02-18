<?php

namespace Imgur;

use Imgur\Auth;
use Imgur\HttpClient;

/**
 * PHP Imgur API wrapper
 * 
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 * 
 * Website: https://github.com/Adyg/php-imgur-api-client
 */

class Client {
    
    /**
     * @var array
     */
    private $options = array(
        'base_url' => 'https://api.imgur.com/3/',

        'client_id' => null,
        'client_secret' => null
    );

    /**
     * The class handling communication with Imgur servers
     *
     * @var HttpClient
     */
    private $httpClient;    

    /**
     * The class handling authentication
     *
     * @var \Imgur\Auth\AuthInterface
     */
    private $authenticationClient;    
    
    
    /**
     * Instantiate a new Imgur client
     *
     * @param null|HttpClientInterface $httpClient Imgur http client
     */
    public function __construct(Auth\AuthInterface $authenticationClient = null, HttpClient\HttpClientInterface $httpClient = null) {
        $this->httpClient = $httpClient;
        $this->authenticationClient = $authenticationClient;
    }  

    /**
     * @param string $name
     * @param Imgur\Pager\PagerInterface $pager
     * 
     * @return ApiInterface
     *
     * @throws InvalidArgumentException
     */
    public function api($name, $pager = null) {
        if(!$this->getAccessToken()) {
            $this->sign();
        }
        
        switch ($name) {
            case 'account':
                $api = new Api\Account($this, $pager);
                break;
            
            case 'album':
                $api = new Api\Album($this, $pager);
                break;
            
            case 'comment':
                $api = new Api\Comment($this, $pager);
                break;
            
            case 'gallery':
                $api = new Api\Gallery($this, $pager);
                break;
            
            case 'image':
                $api = new Api\Image($this, $pager);
                break;
            
            case 'conversation':
                $api = new Api\Conversation($this, $pager);
                break;
            
            case 'notification':
                $api = new Api\Notification($this, $pager);
                break;
            
            case 'memegen':
                $api = new Api\Memegen($this, $pager);
                break;
            
            default:
                throw new InvalidArgumentException('API Method not supported: '.$name);
        }

        return $api;
    }    

    /**
     * @return HttpClient
     */
    public function getHttpClient() {
        if (null === $this->httpClient) {
            $this->httpClient = new \Imgur\HttpClient\HttpClient($this->options);
        }

        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClient\HttpClientInterface $httpClient) {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getOption($name) {
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
    public function setOption($name, $value) {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        $this->options[$name] = $value;
    }

    /**
     * Retrieves the Auth object and also instantiates it if not already present
     * 
     * @return AuthInterface
     */
    public function getAuthenticationClient() {
        if(empty($this->authenticationClient)) {
            $this->authenticationClient = new Auth\OAuth2($this->getOption('client_id'), $this->getOption('client_secret'));
        }
        
        return $this->authenticationClient;
    }
    
    /**
     * Proxy method for the authentication objects URL building method
     * 
     * @param string $responseType
     * @param string $state
     * @return string
     */
    public function getAuthenticationUrl($responseType = 'code', $state = null) {
        $authenticationClient = $this->getAuthenticationClient();
        
        return $authenticationClient->getAuthenticationUrl($responseType, $state);
    }
    
    /**
     * Proxy method for exchanging a code for an access token/a pin for an access token
     * 
     * @param string $code
     * @param string $responseType
     * @return string
     */
    public function requestAccessToken($code, $responseType = 'code') {
        $authenticationClient = $this->getAuthenticationClient();
        
        return $authenticationClient->requestAccessToken($code, $responseType, $this->getHttpClient());
    }

    /**
     * Proxy method for retrieving the access token
     * 
     * @return array
     */
    public function getAccessToken() {
        $authenticationClient = $this->getAuthenticationClient();
        
        return $authenticationClient->getAccessToken();
    }    

    /**
     * Proxy method for checking if the access token expired
     * 
     * @return array
     */    
    public function checkAccessTokenExpired() {
        $authenticationClient = $this->getAuthenticationClient();
        
        return $authenticationClient->checkAccessTokenExpired();
    }
    
    /**
     * Proxy method for refreshing an access token
     * 
     * @return array
     */
    public function refreshToken() {
        $authenticationClient = $this->getAuthenticationClient();
        $httpClient = $this->getHttpClient();
        
        return $authenticationClient->refreshToken($httpClient);        
    }
    
    /**
     * Proxy method for setting an access token
     * 
     * @param array $token
     */
    public function setAccessToken($token) {
        $authenticationClient = $this->getAuthenticationClient();
        $httpClient = $this->getHttpClient();
        
        $authenticationClient->setAccessToken($token, $httpClient);        
    }
    
    /**
     * Proxy method for signing a request
     * 
     */
    public function sign() {
        $authenticationClient = $this->getAuthenticationClient();
        $httpClient = $this->getHttpClient();
        
        $authenticationClient->sign($httpClient);        
    }
}