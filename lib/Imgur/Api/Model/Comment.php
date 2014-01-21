<?php

namespace Imgur\Api\Model;

/**
 * Model for Comment
 * 
 * @link https://api.imgur.com/models/comment
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Comment {
    
    /**
     * The ID for the comment
     * 
     * @var string
     */
    private $id;
    
    /**
     * The ID of the image that the comment is for
     * 
     * @var string
     */
    private $imageId;
    
    /**
     * The comment itself.
     * 
     * @var string
     */
    private $caption;
    
    /**
     * Username of the author of the comment
     * 
     * @var string
     */
    private $author;
    
    /**
     * The account ID for the author
     * 
     * @var integer
     */
    private $authorId;
    
    /**
     * If this comment was done to an album
     * 
     * @var boolean
     */
    private $onAlbum;
    
    /**
     * The ID of the album cover image, this is what should be displayed for album comments
     * 
     * @var integer
     */
    private $albumCover;
    
    /**
     * Number of upvotes for the comment
     * 
     * @var integer
     */
    private $ups;
    
    /**
     * Number of downvotes for the comment
     * 
     * @var integer
     */
    private $downs;
    
    /**
     * The number of upvotes - downvotes
     * 
     * @var float
     */
    private $points;
    
    /**
     * Timestamp of creation, epoch time
     * 
     * @var integer
     */
    private $datetime;
    
    /**
     * If this is a reply, this will be the value of the comment_id for the caption this a reply for.
     * 
     * @var integer
     */
    private $parentId;
    
    /**
     * Marked true if this caption has been deleted
     * 
     * @var boolean
     */
    private $deleted;
    
    /**
     * All of the replies for this comment. If there are no replies to the comment then this is an empty set.
     * 
     * @var \Imgur\Api\Model\Comment|array
     */
    private $children;


    /**
     * Build the Comment object based on an array
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\Comment
     */         
    public function __construct($parameters) {
        if(!empty($parameters['data'])) {
            $parameters = $parameters['data'];
        }
        
        $this->setAlbumCover($parameters['album_cover'])
             ->setAuthor($parameters['author'])
             ->setAuthorId($parameters['author_id'])
             ->setCaption($parameters['caption'])
             ->setDatetime($parameters['datetime'])
             ->setDeleted($parameters['deleted'])
             ->setDowns($parameters['downs'])
             ->setId($parameters['id'])
             ->setImageId($parameters['image_id'])
             ->setOnAlbum($parameters['on_album'])
             ->setParentId($parameters['parent_id'])
             ->setPoints($parameters['points'])
             ->setUps($parameters['ups']);

        if(!empty($parameters['children'])) {
            $children = array();
            foreach($parameters['children'] as $comment) {
                $children[] = new Comment($comment);
            }
            $this->setComments($children);
        }        
        
        return $this;
    }
    
    /**
     * The ID for the comment
     * 
     * @return integer
     */
    public function getId() {
        
        return $this->id;
    }

    /**
     * The ID for the comment
     * 
     * @param integer $id
     * @return \Imgur\Api\Model\Comment
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * The ID of the image that the comment is for
     * 
     * @return integer
     */
    public function getImageId() {
        
        return $this->imageId;
    }

    /**
     * The ID of the image that the comment is for
     * 
     * @param integer $imageId
     * @return \Imgur\Api\Model\Comment
     */
    public function setImageId($imageId) {
        $this->imageId = $imageId;
        
        return $this;        
    }

    /**
     * The comment itself.
     * 
     * @return string
     */
    public function getCaption() {
        
        return $this->caption;
    }

    /**
     * The comment itself.
     * 
     * @param string $caption
     * @return \Imgur\Api\Model\Comment
     */
    public function setCaption($caption) {
        $this->caption = $caption;
                
        return $this;
    }

    /**
     * Username of the author of the comment
     * 
     * @return string
     */
    public function getAuthor() {
        
        return $this->author;
    }

    /**
     * Username of the author of the comment
     * 
     * @param string $author
     * @return \Imgur\Api\Model\Comment
     */
    public function setAuthor($author) {
        $this->author = $author;
                
        return $this;
    }

    /**
     * The account ID for the author
     * 
     * @return integer
     */
    public function getAuthorId() {
        
        return $this->authorId;
    }

    /**
     * The account ID for the author
     * 
     * @param integer $authorId
     * @return \Imgur\Api\Model\Comment
     */
    public function setAuthorId($authorId) {
        $this->authorId = $authorId;
                
        return $this;
    }

    /**
     * If this comment was done to an album
     * 
     * @return boolean
     */
    public function getOnAlbum() {
        
        return $this->onAlbum;
    }

    /**
     * If this comment was done to an album
     * 
     * @param boolean $onAlbum
     * @return \Imgur\Api\Model\Comment
     */
    public function setOnAlbum($onAlbum) {
        $this->onAlbum = $onAlbum;
                
        return $this;
    }

    /**
     * The ID of the album cover image, this is what should be displayed for album comments
     * 
     * @return string
     */
    public function getAlbumCover() {
        
        return $this->albumCover;
    }

    /**
     * The ID of the album cover image, this is what should be displayed for album comments
     * 
     * @param string $albumCover
     * @return \Imgur\Api\Model\Comment
     */
    public function setAlbumCover($albumCover) {
        $this->albumCover = $albumCover;
                
        return $this;
    }

    /**
     * Number of upvotes for the comment
     * 
     * @return integer
     */
    public function getUps() {
        return $this->ups;
    }

    /**
     * Number of upvotes for the comment
     * 
     * @param integer $ups
     * @return \Imgur\Api\Model\Comment
     */
    public function setUps($ups) {
        $this->ups = $ups;
                
        return $this;
    }

    /**
     * Number of downvotes for the comment
     * 
     * @return type
     */
    public function getDowns() {
        
        return $this->downs;
    }

    /**
     * Number of downvotes for the comment
     * 
     * @param integer $downs
     * @return \Imgur\Api\Model\Comment
     */
    public function setDowns($downs) {
        $this->downs = $downs;
                
        return $this;
    }

    /**
     * The number of upvotes - downvotes
     * 
     * @return float
     */
    public function getPoints() {
        
        return $this->points;
    }

    /**
     * The number of upvotes - downvotes
     * 
     * @param float $points
     * @return \Imgur\Api\Model\Comment
     */
    public function setPoints($points) {
        $this->points = $points;
                
        return $this;
    }

    /**
     * Timestamp of creation, epoch time
     * 
     * @return integer
     */
    public function getDatetime() {
        
        return $this->datetime;
    }

    /**
     * Timestamp of creation, epoch time
     * 
     * @param integer $datetime
     * @return \Imgur\Api\Model\Comment
     */
    public function setDatetime($datetime) {
        $this->datetime = $datetime;
                
        return $this;
    }
    
    /**
     * If this is a reply, this will be the value of the comment_id for the caption this a reply for.
     * 
     * @return integer
     */
    public function getParentId() {
        
        return $this->parentId;
    }

    /**
     * If this is a reply, this will be the value of the comment_id for the caption this a reply for.
     * 
     * @param integer $parentId
     * @return \Imgur\Api\Model\Comment
     */
    public function setParentId($parentId) {
        $this->parentId = $parentId;
                
        return $this;
    }

    /**
     * Marked true if this caption has been deleted
     * 
     * @return boolean
     */
    public function getDeleted() {
        
        return $this->deleted;
    }

    /**
     * Marked true if this caption has been deleted
     * 
     * @param boolean $deleted
     * @return \Imgur\Api\Model\Comment
     */
    public function setDeleted($deleted) {
        $this->deleted = $deleted;
                
        return $this;
    }

    /**
     * All of the replies for this comment. If there are no replies to the comment then this is an empty set.
     * 
     * @return \Imgur\Api\Model\Comment|array
     */
    public function getChildren() {
        
        return $this->children;
    }

    /**
     * All of the replies for this comment. If there are no replies to the comment then this is an empty set.
     * 
     * @param \Imgur\Api\Model\Comment|array $children
     * @return \Imgur\Api\Model\Comment
     */
    public function setChildren($children) {
        $this->children = $children;
                
        return $this;
    }    
}
    