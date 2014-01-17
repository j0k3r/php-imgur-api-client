<?php

namespace Imgur\Api\Model;

/**
 * Model for Image
 * 
 * @link https://api.imgur.com/models/image
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Image {
    
    /**
     * The ID for the image
     * @var string 
     */
    private $id;
    
    /**
     * The title of the image.
     * @var string
     */
    private $title;
    
    /**
     * Description of the image.
     * @var string 
     */
    private $description;
    
    /**
     * Time inserted into the gallery, epoch time
     * @var integer
     */
    private $datetime;
    
    /**
     * Image MIME type.
     * @var string 
     */
    private $type;
    
    /**
     * Is the image animated
     * @var boolean
     */
    private $animated;
    
    /**
     * The width of the image in pixels
     * @var integer 
     */
    private $width;
    
    /**
     * The width of the image in pixels
     * @var integer 
     */
    private $height;
    
    /**
     * The size of the image in bytes
     * @var size 
     */
    private $size;
    
    /**
     * The number of image views
     * @var integer
     */
    private $views;
    
    /**
     * Bandwidth consumed by the image in bytes
     * @var integer 
     */
    private $bandwidth;
    
    /**
     * OPTIONAL, the deletehash, if you're logged in as the image owner
     * @var string
     */
    private $deletehash;
    
    /**
     * If the image has been categorized by the Imgur backend then this will contain the section the image belongs in. (funny, cats, adviceanimals, wtf, etc)
     * @var type 
     */
    private $section;
    
    /**
     * The direct link to the the image    
     * @var type 
     */
    private $link;

    /**
     * Build the Image object based on an array
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\Image
     */
    public function __construct($parameters) {
        if(!empty($parameters['data'])) {
            $parameters = $parameters['data'];
        }
        
        $this->setId($parameters['id'])
             ->setTitle($parameters['title'])
             ->setDescription($parameters['description'])
             ->setDatetime($parameters['datetime'])
             ->setType($parameters['description'])
             ->setAnimated($parameters['animated'])
             ->setWidth($parameters['width'])
             ->setHeight($parameters['height'])
             ->setSize($parameters['size'])
             ->setViews($parameters['views'])
             ->setBandwidth($parameters['bandwidth'])
             ->setSection($parameters['section'])
             ->setLink($parameters['link']);
       
        if(!empty($parameters['deletehash'])) {
            $this->setDeletehash($parameters['deletehash']);
        }
        
        return $this;
    }

    /**
     * The account id for the username requested.
     * 
     * @param integer $id
     */    
    public function setId($id) {
        $this->id = $id; 
        
        return $this;
    }

    /**
     * The account id for the username requested.
     * 
     * @return integer|null
     */    
    public function getId() {
        
        return $this->id;
    }

    /**
     * The title of the image
     * 
     * @param string $title
     */    
    public function setTitle($title) {
        $this->title = $title;
        
        return $this;        
    }
    
    /**
     * The title of the image
     * 
     * @return string|null
     */    
    public function getTitle() {
        
        return $this->title;
    }

    /**
     * The description of the image
     * 
     * @param string $description
     */    
    public function setDescription($description) {
        $this->description = $description;
        
        return $this;        
    }
    
    /**
     * The description of the image
     * 
     * @return string|null
     */    
    public function getDescription() {
        
        return $this->description;
    } 

    /**
     * Time inserted into the gallery, epoch time
     * 
     * @param integer $datetime
     */    
    public function setDatetime($datetime) {
        $this->datetime = $datetime;
        
        return $this;        
    }
    
    /**
     * Time inserted into the gallery, epoch time
     * 
     * @return integer|null
     */    
    public function getDatetime() {
        
        return $this->datetime;
    } 

    /**
     * Image MIME type.
     * 
     * @param string $type
     */    
    public function setType($type) {
        $this->type = $type;
        
        return $this;        
    }
    
    /**
     * Image MIME type.
     * 
     * @return string|null
     */    
    public function getType() {
        
        return $this->type;
    } 

    /**
     * Is the image animated
     * 
     * @param boolean $animated
     */    
    public function setAnimated($animated) {
        $this->animated = $animated;
        
        return $this;        
    }
    
    /**
     * Is the image animated
     * 
     * @return boolean|null
     */    
    public function getAnimated() {
        
        return $this->animated;
    }     
    
    /**
     * Width of the image in pixels
     * 
     * @param integer $width
     */    
    public function setWidth($width) {
        $this->width = $width;
        
        return $this;        
    }
    
    /**
     * Width of the image in pixels
     * 
     * @return integer|null
     */    
    public function getWidth() {
        
        return $this->width;
    }     

    /**
     * Height of the image in pixels
     * 
     * @param integer $height
     */    
    public function setHeight($height) {
        $this->height = $height;
        
        return $this;        
    }
    
    /**
     * Height of the image in pixels
     * 
     * @return integer|null
     */    
    public function getHeight() {
        
        return $this->height;
    }     

    /**
     * The size of the image in bytes
     * 
     * @param integer $size
     */    
    public function setSize($size) {
        $this->size = $size;
        
        return $this;        
    }
    
    /**
     * The size of the image in bytes
     * 
     * @return integer|null
     */    
    public function getSize() {
        
        return $this->size;
    }     
    
    /**
     * The number of image views
     * 
     * @param integer $views
     */    
    public function setViews($views) {
        $this->views = $views;
        
        return $this;        
    }
    
    /**
     * The number of image views
     * 
     * @return integer|null
     */    
    public function getViews() {
        
        return $this->views;
    }       
    
    /**
     * Bandwidth consumed by the image in bytes
     * 
     * @param integer $bandwidth
     */    
    public function setBandwidth($bandwidth) {
        $this->bandwidth = $bandwidth;
        
        return $this;        
    }
    
    /**
     * Bandwidth consumed by the image in bytes
     * 
     * @return integer|null
     */    
    public function getBandwidth() {
        
        return $this->bandwidth;
    }       
    
    /**
     * The deletehash
     * 
     * @param string $deletehash
     */    
    public function setDeletehash($deletehash) {
        $this->deletehash = $deletehash;
        
        return $this;        
    }
    
    /**
     * The deletehash
     * 
     * @return string|null
     */    
    public function getDeletehash() {
        
        return $this->deletehash;
    }   
    
    /**
     * If the image has been categorized by the Imgur backend then this will contain the section the image belongs in. (funny, cats, adviceanimals, wtf, etc)
     * 
     * @param string $section
     */    
    public function setSection($section) {
        $this->section = $section;
        
        return $this;        
    }
    
    /**
     * If the image has been categorized by the Imgur backend then this will contain the section the image belongs in. (funny, cats, adviceanimals, wtf, etc)
     * 
     * @return string|null
     */    
    public function getSection() {
        
        return $this->section;
    }    
    
    /**
     * The direct link to the the image    
     * 
     * @param string $link
     */    
    public function setLink($link) {
        $this->link = $link;
        
        return $this;        
    }
    
    /**
     * The direct link to the the image    
     * 
     * @return string|null
     */    
    public function getLink() {
        
        return $this->link;
    }       
}