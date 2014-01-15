<?php

namespace Imgur\Auth;

use Imgur\HttpClient\Listener\AuthListener;

/**
 * Authentication class used for handling OAuth2
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class OAuth2 implements \Imgur\Auth\AuthInterface {
    /**
     * Indicates the client that is making the request.
     * 
     * @var string 
     */
    private $clientId;

    /**
     * The client_secret for the application
     * 
     * @var string 
     */
    private $clientSecret;    

    /**
     * The access token and refresh token values
     * 
     * @var array
     */
    private $token;    
        
    const AUTHORIZATION_ENDPOINT = 'https://api.imgur.com/oauth2/authorize';
    const ACCESS_TOKEN_ENDPOINT = 'https://api.imgur.com/oauth2/token';
    
    /**
     * Instantiates the OAuth2 class, but does not trigger the authentication process
     * 
     * @param string $clientSecret
     * @param string $clientId
     */
    public function __construct($clientId, $clientSecret) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }
    
    /**
     * Generates the authentication URL to which a user should be pointed at in order to start the OAuth2 process
     * 
     * @param string $responseType
     * @param null|string $state
     * @return string
     */
    public function getAuthenticationURL($responseType = 'code', $state = null) {
        $httpQueryParameters = array(
            'client_id' => $this->clientId,
            'response_type' => $responseType,
            'state' => $state
        );
        
        $httpQueryParameters = http_build_query($httpQueryParameters);
        
        return self::AUTHORIZATION_ENDPOINT.'?'.$httpQueryParameters;
    }

    /**
     * Exchanges a code/pin for an access token
     * 
     * @param string $code
     * @param string $requestType
     * @return string
     */
    public function requestAccessToken($code, $requestType, $httpClient) {
        $response = $httpClient->post(self::ACCESS_TOKEN_ENDPOINT, 
                                      array(
                                          'client_id' => $this->clientId,
                                          'client_secret' => $this->clientSecret,
                                          'grant_type' => 'authorization_code',
                                          'code' => $code
                                      ));

        $responseBody = $response->getBody(true);
        $responseBody = json_decode($responseBody, true);
        
        if($response->getStatusCode() == 200) {
            $this->setAccessToken($responseBody);
        }
        else {
            throw new AuthException('Request for access token failed. '.$responseBody['error'], $response->getStatusCode());
        }
        
        $this->sign($httpClient);
        
        return $response;
    }
    
    public function authenticate($service) {
        
    }

    public function refreshToken($refreshToken) {
        
    }

    public function revokeToken() {
        
    }

    /**
     * Stores the access token, refresh token and expiration date
     * 
     * @param array $token
     * @throws AuthException
     * @return array
     */
    public function setAccessToken($token) {
        $latency = 30; //number of seconds which will be substracted from the token expiration time, to compensate for any request latency before getting a new token
        
        if ($token == null) {
          throw new AuthException('Token is not a valid json string.');
        }
        
        if (! isset($token['access_token'])) {
          throw new AuthException('Access token could not be retrieved from the decoded json response.');
        }

        $this->token = $token;  
        $this->token['created_at'] = time();
        $this->token['expires_in'] = $this->token['expires_in'] - $latency;
    }

    /**
     * Getter for the current access token
     * 
     * @return array
     */
    public function getAccessToken() {
        
        return $this->token;
    }
    
    /**
     * Check if the current access token (if present), is still usable
     * 
     * @return bool
     */
    public function checkAccessTokenExpired() {
        $expirationTime = $this->token['created'] + $this->token['expires_in'];

        return $expirationTime < time();        
    }
    
    /**
     * Attaches the triggers needed for attaching the header signature to each request
     * 
     * @param HttpClient $httpClient
     */
    public function sign($httpClient) {
        $token = $this->getAccessToken();
        
        $this->addListener($httpClient, 'request.before_send', array(
            new AuthListener($token), 'onRequestBeforeSend'
        ));        
    }
    
    /**
     * Attaches a listener to a HttpClient event
     * 
     * @param HttpClient $httpClient
     * @param string $eventName
     * @param array $listener
     */
    public function addListener($httpClient, $eventName, $listener) {
        $httpClient->addListener($eventName, $listener);
    }    
}