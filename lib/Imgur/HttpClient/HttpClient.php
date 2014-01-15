<?php

namespace Imgur\HttpClient;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Imgur\Exception;

use Imgur\Listener\ErrorListener;

/**
 * Basic client for performing HTTP requests
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class HttpClient implements \Imgur\HttpClient\HttpClientInterface {
    
    /**
     * The Guzzle instance
     * 
     * @var Guzzle\Http\Client
     */
    private $client;
    
    /**
     * HTTP Client Settings
     * 
     * @var array 
     */
    private $options = array(
        'headers' => array()
    );
    
    /**
     * 
     * @param array $options
     * @param \Guzzle\Http\ClientInterface $client
     */
    public function __construct(array $options = array()) {
        $this->options = array_merge($options, $this->options);
        $this->client = new GuzzleClient($this->options['base_url']);
        
        $this->addListener('request.error', array(
                            new ErrorListener($this->options), 
                            'onRequestError')
                );
    }
    
    /**
     * {@inheritDoc}
     */    
    public function get($url, array $parameters = array()) {
        
        return $this->performRequest($url, $parameters, 'GET');
    }
    
    /**
     * {@inheritDoc}
     */
    public function post($url, array $parameters = array()) {

        return $this->performRequest($url, $parameters, 'POST');
    }
    
    /**
     * {@inheritDoc}
     */
    public function performRequest($url, $parameters, $httpMethod = 'GET') {
        $request = $this->createRequest($url, $parameters, $httpMethod);

        try {
            $response = $this->client->send($request);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage());
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $response;        
    }
    
    /**
     * {@inheritDoc}
     */
    public function createRequest($url, $parameters, $httpMethod = 'GET') {

        return $this->client->createRequest($httpMethod, $url, $this->options['headers'], $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function parseResponse($response) {
        $responseBody = $response->getBody(true);
        $responseBody = json_decode($responseBody, true);
        
        return $response;
    }
    
    /**
     * Attaches a listener to a HttpClient event
     * 
     * @param HttpClient $httpClient
     * @param string $eventName
     * @param array $listener
     */
    public function addListener($eventName, $listener) {
        $this->client->getEventDispatcher()->addListener($eventName, $listener);
    }    
    
}