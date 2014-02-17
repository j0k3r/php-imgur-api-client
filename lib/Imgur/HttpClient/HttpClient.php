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
        'headers' => array(),
        'body' => array()
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
    public function delete($url, array $parameters = array()) {

        return $this->performRequest($url, $parameters, 'DELETE');
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

            return $response;        
        } catch (\Imgur\Exception\LogicException $e) {
            error_log($e->getMessage());
        } catch (\Imgur\Exception\RuntimeException $e) {
            error_log($e->getMessage());
        } catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
            $responseData = $e->getResponse()->json();
            error_log('Request to: '.$responseData['data']['request'].' failed with: ['.$responseData['status'].']"'.$responseData['data']['error'].'"');
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    public function createRequest($url, $parameters, $httpMethod = 'GET') {
        if($httpMethod == 'POST' || $httpMethod == 'DELETE') {

            return $this->client->createRequest($httpMethod, $url, $this->options['headers'], $parameters);
        }
        else {

            return $this->client->createRequest($httpMethod, $url, $this->options['headers'], $this->options['body'], $parameters);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function parseResponse($response) {
        $responseBody = array('data' => array(), 'success' => false);
        
        if($response) {
            $responseBody = json_decode($response->getBody(true), true);
        }
        
        return $responseBody;
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