<?php

namespace Imgur;

use Imgur\Auth;
use Imgur\HttpClient;

/**
 * PHP Imgur API wrapper
 * 
 * @author Adrian Ghiuta <ady.ghiuta@gmail.com>
 * 
 * Website: https://bitbucket.org/adrian_ghiuta/php-imgur-api
 */

class Client {
    
    /**
     * @var array
     */
    private $options = array(
        'base_url' => 'https://api.imgur.com/3/',

        'timeout' => 10,
        
        'client_id' => null,
        'client_secret' => null,
        
        'api_upload_limit' => 1250,
        'api_call_limit' => 12500
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
    public function __construct(Auth\AuthInterface $authenticationClient = null, HttpClientInterface $httpClient = null) {
        $this->httpClient = $httpClient;
        $this->authenticationClient = $authenticationClient;
    }  

    /**
     * @param string $name
     *
     * @return ApiInterface
     *
     * @throws InvalidArgumentException
     */
    public function api($name) {
        switch ($name) {
            case 'test':
                $this->httpClient->get('http://www.google.com');
                break;
            case 'me':
            case 'current_user':
                $api = new Api\CurrentUser($this);
                break;

            case 'git':
            case 'git_data':
                $api = new Api\GitData($this);
                break;

            case 'gist':
            case 'gists':
                $api = new Api\Gists($this);
                break;

            case 'issue':
            case 'issues':
                $api = new Api\Issue($this);
                break;

            case 'markdown':
                $api = new Api\Markdown($this);
                break;

            case 'organization':
            case 'organizations':
                $api = new Api\Organization($this);
                break;

            case 'pr':
            case 'pull_request':
            case 'pull_requests':
                $api = new Api\PullRequest($this);
                break;

            case 'repo':
            case 'repos':
            case 'repository':
            case 'repositories':
                $api = new Api\Repo($this);
                break;

            case 'team':
            case 'teams':
                $api = new Api\Organization\Teams($this);
                break;

            case 'user':
            case 'users':
                $api = new Api\User($this);
                break;

            case 'authorization':
            case 'authorizations':
                $api = new Api\Authorizations($this);
                break;

            default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
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
    public function setHttpClient(HttpClientInterface $httpClient) {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
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
}