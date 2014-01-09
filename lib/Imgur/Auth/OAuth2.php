<?php

namespace Imgur\Auth;

require_once __DIR__.'/AuthInterface.php';

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
    
    const AUTHORIZATION_ENDPOINT = 'https://api.imgur.com/oauth2/authorize';
    const ACCESS_TOKEN_ENDPOINT = 'https://api.imgur.com/oauth2/token';
    
    /**
     * Instantiates the OAuth2 class, but does not trigger the authentication process
     * 
     * @param string $clientId
     */
    public function __construct($clientId) {
        $this->clientId = $clientId;
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
    public function requestAccessToken($code, \Imgur\HttpClient $httpClient, $requestType = 'code') {
        $response = $httpClient->post('');
    }
    
    public function authenticate($service) {
        
    }

    public function getAccessToken() {
        
    }

    public function refreshToken($refreshToken) {
        
    }

    public function revokeToken() {
        
    }

    public function setAccessToken($accessToken) {
        
    }

    public function sign($request) {
        
    }

}