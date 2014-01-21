<?php

namespace Imgur\Api\Model;

/**
 * Model for Gallery Profile
 * 
 * @link https://api.imgur.com/models/gallery_profile
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class GalleryProfile {
    
    /**
     * Total number of comments the user has made in the gallery
     * 
     * @var integer
     */
    private $totalGalleryComments;
    
    /**
     * Total number of images liked by the user in the gallery
     * 
     * @var integer
     */
    private $totalGalleryLikes;
    
    /**
     * Total number of images submitted by the user.
     * 
     * @var integer
     */
    private $totalGallerySubmissions;
    
    /**
     * An array of trophies that the user has.
     * @var \Imgur\Api\Model\Trophy|array
     */
    private $trophies;
    
    /**
     * Build the GalleryProfile object from an array
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\GalleryProfile
     */
    public function __construct($parameters) {
        $this->setTotalGalleryComments($parameters['total_gallery_comments'])
             ->setTotalGalleryLikes($parameters['total_gallery_likes'])
             ->setTotalGallerySubmissions($parameters['total_gallery_submissions']);
        
        if(!empty($parameters['trophies'])) {
            $trophies = array();
            foreach($parameters['trophies'] as $trophyData) {
                $trophies[] = new Trophy($trophyData);
            }
            
            $this->setTrophies($trophies);
        }
        
        return $this;
    }
    
    /**
     * Total number of comments the user has made in the gallery
     * 
     * @return integer
     */
    public function getTotalGalleryComments() {
        
        return $this->totalGalleryComments;
    }

    /**
     * Total number of comments the user has made in the gallery
     * 
     * @param integer $totalGalleryComments
     * @return \Imgur\Api\Model\GalleryProfile
     */
    public function setTotalGalleryComments($totalGalleryComments) {
        $this->totalGalleryComments = $totalGalleryComments;
        
        return $this;
    }

    /**
     * Total number of images liked by the user in the gallery
     * 
     * @return integer
     */
    public function getTotalGalleryLikes() {
        
        return $this->totalGalleryLikes;
    }

    /**
     * Total number of images liked by the user in the gallery
     * 
     * @param integer $totalGalleryLikes
     * @return \Imgur\Api\Model\GalleryProfile
     */
    public function setTotalGalleryLikes($totalGalleryLikes) {
        $this->totalGalleryLikes = $totalGalleryLikes;
        
        return $this;        
    }

    /**
     * Total number of images submitted by the user.
     * 
     * @return integer
     */
    public function getTotalGallerySubmissions() {
        
        return $this->totalGallerySubmissions;
    }

    /**
     * Total number of images submitted by the user.
     * 
     * @param integer $totalGallerySubmissions
     * @return \Imgur\Api\Model\GalleryProfile
     */
    public function setTotalGallerySubmissions($totalGallerySubmissions) {
        $this->totalGallerySubmissions = $totalGallerySubmissions;
        
        return $this;        
    }

    /**
     * An array of trophies that the user has.
     * 
     * @return \Imgur\Api\Model\Trophy|array
     */
    public function getTrophies() {
        
        return $this->trophies;
    }

    /**
     * An array of trophies that the user has.
     * 
     * @param \Imgur\Api\Model\Trophy|array $trophies
     * @return \Imgur\Api\Model\GalleryProfile
     */
    public function setTrophies($trophies) {
        $this->trophies = $trophies;
        
        return $this;        
    }    
}