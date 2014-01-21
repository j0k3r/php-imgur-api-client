<?php

namespace Imgur\Api\Model;

/**
 * Model for Album
 * 
 * @link https://api.imgur.com/models/album
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Album {
    
    /**
     * The ID for the album
     * @var string 
     */
    private $id;
    
    /**
     * The title of the album in the gallery
     * @var string
     */
    private $title;
    
    /**
     * The description of the album in the gallery
     * @var string 
     */
    private $description;
    
    /**
     * Time inserted into the gallery, epoch time
     * @var integer
     */
    private $datetime;
    
    /**
     * The ID of the album cover image
     * @var string
     */
    private $cover;
    
    /**
     * The account username or null if it's anonymous.
     * @var string 
     */
    private $accountUrl;
    
    /**
     * The privacy level of the album, you can only view public if not logged in as album owner
     * @var type 
     */
    private $privacy;
    
    /**
     * The view layout of the album.
     * @var string 
     */
    private $layout;
    
    /**
     * The number of album views
     * @var integer 
     */
    private $views;
    
    /**
     * The URL link to the album
     * @var string
     */
    private $link;
    
    /**
     * The deletehash, if you're logged in as the album owner
     * @var string 
     */
    private $deletehash;
    
    /**
     * The total number of images in the album (only available when requesting the direct album)
     * @var integer 
     */
    private $imagesCount;
    
    /**
     * An array of all the images in the album (only available when requesting the direct album)
     * @var Imgur\Api\Model\Image
     */
    private $images;

    /**
     * Build the Album object based on an array
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\Album
     */    
    public function __construct($parameters) {
        if(!empty($parameters['data'])) {
            $parameters = $parameters['data'];
        }
        
        $this->setId($parameters['id'])
             ->setTitle($parameters['title'])
             ->setDescription($parameters['description'])
             ->setDatetime($parameters['datetime'])
             ->setCover($parameters['cover'])
             ->setAccountUrl($parameters['account_url'])
             ->setPrivacy($parameters['privacy'])
             ->setLayout($parameters['layout'])
             ->setViews($parameters['views'])
             ->setLink($parameters['link'])
             ->setImagesCount($parameters['images_count']);
       
        if(!empty($parameters['deletehash'])) {
            $this->setDeletehash($parameters['deletehash']);
        }
        
        if(!empty($parameters['images'])) {
            $images = array();
            foreach($parameters['images'] as $image) {
                $images[] = new Image($image);
            }
            $this->setImages($images);
        }
        
        return $this;        
    }
    
    /**
     * The ID for the album
     * 
     * @return integer
     */
    public function getId() {
        
        return $this->id;
    }
    
    /**
     * The ID for the album
     * 
     * @param integer $id
     * @return \Imgur\Api\Model\Image
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * The title of the album in the gallery
     * 
     * @return string
     */
    public function getTitle() {
        
        return $this->title;
    }
    
    /**
     * The title of the album in the gallery
     * 
     * @param string $title
     * @return \Imgur\Api\Model\Image
     */
    public function setTitle($title) {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * The description of the album in the gallery
     * 
     * @return string
     */
    public function getDescription() {
        
        return $this->description;
    }
    
    /**
     * The description of the album in the gallery
     * 
     * @param string $description
     * @return \Imgur\Api\Model\Image
     */
    public function setDescription($description) {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * Time inserted into the gallery, epoch time
     * 
     * @return integer
     */
    public function getDatetime() {
        
        return $this->datetime;
    }
    
    /**
     * Time inserted into the gallery, epoch time
     * 
     * @param integer $datetime
     * @return \Imgur\Api\Model\Image
     */
    public function setDatetime($datetime) {
        $this->datetime = $datetime;
        
        return $this;
    }

    /**
     * The ID of the album cover image
     * 
     * @return string
     */
    public function getCover() {
        
        return $this->cover;
    }
    
    /**
     * The ID of the album cover image
     * 
     * @param string $cover
     * @return \Imgur\Api\Model\Image
     */
    public function setCover($cover) {
        $this->cover = $cover;
        
        return $this;
    }

    /**
     * The account username or null if it's anonymous.
     * 
     * @return string
     */
    public function getAccountUrl() {
        
        return $this->accountUrl;
    }

    /**
     * The account username or null if it's anonymous.
     * 
     * @param string $accountUrl
     * @return \Imgur\Api\Model\Image
     */
    public function setAccountUrl($accountUrl) {
        $this->accountUrl = $accountUrl;
        
        return $this;
    }

    /**
     * The privacy level of the album, you can only view public if not logged in as album owner
     * 
     * @return string
     */
    public function getPrivacy() {
        
        return $this->privacy;
    }

    /**
     * The privacy level of the album, you can only view public if not logged in as album owner
     * 
     * @param string $privacy
     * @return \Imgur\Api\Model\Image
     */
    public function setPrivacy($privacy) {
        $this->privacy = $privacy;
        
        return $this;
    }

    /**
     * The view layout of the album.
     * 
     * @return string
     */
    public function getLayout() {
        
        return $this->layout;
    }

    /**
     * The view layout of the album.
     * 
     * @param string $layout
     * @return \Imgur\Api\Model\Image
     */
    public function setLayout($layout) {
        $this->layout = $layout;
        
        return $this;
    }

    /**
     * The number of album views
     * 
     * @return integer
     */
    public function getViews() {
        
        return $this->views;
    }

    /**
     * The number of album views
     * 
     * @param integer $views
     * @return \Imgur\Api\Model\Image
     */
    public function setViews($views) {
        $this->views = $views;
        
        return $this;
    }

    /**
     * The URL link to the album
     * 
     * @return string
     */
    public function getLink() {
        
        return $this->link;
    }

    /**
     * The URL link to the album
     * 
     * @param string $link
     * @return \Imgur\Api\Model\Image
     */
    public function setLink($link) {
        $this->link = $link;
        
        return $this;
    }

    /**
     * The deletehash
     * 
     * @return string
     */
    public function getDeletehash() {
        
        return $this->deletehash;
    }

    /**
     * The deletehash
     * 
     * @param string $deletehash
     * @return \Imgur\Api\Model\Image
     */
    public function setDeletehash($deletehash) {
        $this->deletehash = $deletehash;
        
        return $this;
    }

    /**
     * The total number of images in the album (only available when requesting the direct album)
     * 
     * @return integer
     */
    public function getImagesCount() {
        
        return $this->imagesCount;
    }

    /**
     * The total number of images in the album (only available when requesting the direct album)
     * 
     * @param integer $imagesCount
     * @return \Imgur\Api\Model\Image
     */
    public function setImagesCount($imagesCount) {
        $this->imagesCount = $imagesCount;
        
        return $this;
    }

    /**
     * An array of all the images in the album
     * 
     * @return array
     */
    public function getImages() {
        
        return $this->images;
    }

    /**
     * An array of all the images in the album
     * 
     * @param array $images
     * @return \Imgur\Api\Model\Image
     */
    public function setImages($images) {
        $this->images = $images;
        
        return $this;
    }    
}