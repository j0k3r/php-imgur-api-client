<?php

namespace Imgur\Api\Model;

/**
 * Model for Gallery Album
 * 
 * @link https://api.imgur.com/models/gallery_album
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class GalleryAlbum extends Album {
    /**
     * Upvotes for the image
     * @var integer 
     */
    private $ups;
    
    /**
     * Number of downvotes for the image
     * @var integer 
     */
    private $downs;
    
    /**
     * Imgur popularity score
     * @var integer 
     */
    private $score;
    
    /**
     * If it's an album or not
     * @var boolean
     */
    private $isAlbum;
    
    /**
     * The current user's vote on the album. null if not signed in or if the user hasn't voted on it.
     * 
     * @var string
     */
    private $vote;

    /**
     * Build the GalleryImage object based on an array
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\GalleryImage
     */
    
    public function __construct($parameters) {
        parent::__construct($parameters);
        
        $this->setUps($parameters['ups'])
             ->setDowns($parameters['downs'])
             ->setScore($parameters['score'])
             ->setIsAlbum($parameters['is_album'])
             ->setVote($parameters['vote']);
        
        return $this;
    }
    
    /**
     * Upvotes for the album
     * 
     * @param integer $ups
     * @return \Imgur\Api\Model\GalleryAlbum
     */
    public function setUps($ups) {
        $this->ups = $ups;
        
        return $this;
    }

    /**
     * Upvotes for the album
     * 
     * @return integer $ups
     */
    public function getUps() {
        
        return $this->ups;
    }   
    
    /**
     * Downvotes for the album
     * 
     * @param integer $downs
     * @return \Imgur\Api\Model\GalleryAlbum
     */
    public function setDowns($downs) {
        $this->downs = $downs;
        
        return $this;
    }

    /**
     * Downvotes for the album
     * 
     * @return integer $downs
     */
    public function getDowns() {
        
        return $this->downs;
    }   
    
    /**
     * Imgur popularity score
     * 
     * @param integer $score
     * @return \Imgur\Api\Model\GalleryAlbum
     */
    public function setScore($score) {
        $this->score = $score;
        
        return $this;
    }

    /**
     * Imgur popularity score
     * 
     * @return integer $score
     */
    public function getScore() {
        
        return $this->score;
    }     
    
    /**
     * If it's an album or not
     * 
     * @param boolean $isAlbum
     * @return \Imgur\Api\Model\GalleryAlbum
     */
    public function setIsAlbum($isAlbum) {
        $this->isAlbum = $isAlbum;
        
        return $this;
    }

    /**
     * If it's an album or not
     * 
     * @return boolean $isAlbum
     */
    public function getIsAlbum() {
        
        return $this->isAlbum;
    }     
    
    /**
     * The current user's vote on the album. null if not signed in or if the user hasn't voted on it.
     * 
     * @return string
     */
    public function getVote() {
        
        return $this->vote;
    }

    /**
     * The current user's vote on the album. null if not signed in or if the user hasn't voted on it.
     * 
     * @param string $vote
     * @return \Imgur\Api\Model\GalleryAlbum
     */
    public function setVote($vote) {
        $this->vote = $vote;
        
        return $this;
    }    
}