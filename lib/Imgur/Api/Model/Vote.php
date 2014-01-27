<?php

namespace Imgur\Api\Model;

/**
 * Model for Vote
 * 
 * @link https://api.imgur.com/models/vote
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Vote {
    /**
     * Number of upvotes
     * 
     * @var integer
     */
    private $ups;
    
    /**
     * Number of downvotes
     * 
     * @var integer
     */
    private $downs;
    
    /**
     * Build the Vote object based on an array
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\Vote
     */         
    public function __construct($parameters) {
        if(!empty($parameters['data'])) {
            $parameters = $parameters['data'];
        }
        
        $this->setDowns($parameters['downs'])
             ->setUps($parameters['ups']);

        return $this;
    }    

    /**
     * Number of upvotes
     * 
     * @return integer
     */
    public function getUps() {
        
        return $this->ups;
    }

    /**
     * Number of upvotes
     * 
     * @param integer $ups
     * @return \Imgur\Api\Model\Vote
     */
    public function setUps($ups) {
        $this->ups = $ups;
                
        return $this;
    }

    /**
     * Number of downvotes
     * 
     * @return type
     */
    public function getDowns() {
        
        return $this->downs;
    }

    /**
     * Number of downvotes
     * 
     * @param integer $downs
     * @return \Imgur\Api\Model\Vote
     */
    public function setDowns($downs) {
        $this->downs = $downs;
                
        return $this;
    }
    
    
}