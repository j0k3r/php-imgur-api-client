<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Gallery
 * 
 * @link https://api.imgur.com/endpoints/gallery
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Gallery extends AbstractApi {
    
    /**
     * Returns the images in the gallery. For example the main gallery is https://api.imgur.com/3/gallery/hot/viral/0.json
     * 
     * @param string $section (hot | top | user)
     * @param string $sort (viral | time)
     * @param integer $page
     * @param string $window (day | week | month | year | all)
     * @param boolean $showViral
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum | array
     */
    public function gallery($section = 'hot', $sort = 'viral', $page = 0, $window = 'day', $showViral = true) {
        $parameters = $this->get('gallery/'.$section.'/'.$sort.'/'.$window.'/'.$page, array('showViral' => var_export($showViral, true)));

        return $this->createAlbumOrImageObjects($parameters);
    }
    
    /**
     * View images for memes subgallery
     * 
     * @param string $sort (viral | time)
     * @param integer $page
     * @param string $window (day | week | month | year | all)
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum | array
     */
    public function memesSubgallery($sort = 'viral', $page = 0, $window = 'day') {
        $parameters = $this->get('gallery/'.$sort.'/'.$window.'/'.$page);

        return $this->createAlbumOrImageObjects($parameters);        
    }
    
    /**
     * View a single image in the memes gallery
     * 
     * @param type $imageId
     * @return \Imgur\Api\Model\Image
     */
    public function memeSubgalleryImage($imageId) {
        $parameters = $this->get('gallery/g/memes/'.$imageId);
        
        return new Model\Image($parameters);
    }
    
    /**
     * View gallery images for a sub-reddit
     *
     * @param string $subreddit (e.g pics - A valid sub-reddit name)
     * @param string $sort (viral | time)
     * @param integer $page
     * @param string $window (day | week | month | year | all)
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum | array
     */
    public function subredditGalleries($subreddit, $sort = 'viral', $page = 0, $window = 'day') {
        $parameters = $this->get('gallery/r/'.$subreddit.'/'.$sort.'/'.$window.'/'.$page);

        return $this->createAlbumOrImageObjects($parameters);            
    }
    
    /**
     * View a single image in the subreddit
     * 
     * @param string $subreddit (e.g pics - A valid sub-reddit name)
     * @param string $imageId
     * @return \Imgur\Api\Model\Image
     */
    public function subredditImage($subreddit, $imageId) {
        $parameters = $this->get('gallery/r/'.$subreddit.'/'.$imageId);
        
        return new Model\Image($parameters);
    }
    
    /**
     * Search the gallery with a given query string.
     * 
     * @param string $query
     * @param string $sort (viral | time)
     * @param integer $page
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum | array
     */
    public function search($query, $sort = 'time', $page = 0) {
        $parameters = $this->get('gallery/search/'.$sort.'/'.$page, array('q' => $query));

        return $this->createAlbumOrImageObjects($parameters);              
    }
    
    /**
     * Returns a random set of gallery images.
     *
     * @param integer $page
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum | array
     */
    public function randomGalleryImages($page = 0) {
        $parameters = $this->get('gallery/random/random/'.$page);

        return $this->createAlbumOrImageObjects($parameters);              
    }
    
    /**
     * Add an Album or Image to the Gallery.
     *
     * @param string $imageOrAlbumId
     * @param array $data
     * @link https://api.imgur.com/endpoints/gallery#to-gallery
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum
     */
    public function submitToGallery($imageOrAlbumId, $data) {
        $parameters = $this->post('gallery/'.$imageOrAlbumId, $data);

        return $this->createAlbumOrImageObjects($parameters);           
    }
    
    /**
     * Remove an image from the gallery. You must be logged in as the owner of the item to do this action.
     *
     * @param string $imageOrAlbumId
     * @return \Imgur\Api\Model\Basic
     */
    public function removeFromGallery($imageOrAlbumId) {
        $parameters = $this->delete('gallery/'.$imageOrAlbumId);

        return new Model\Basic($parameters);           
    }
    
    /**
     * Get additional information about an album in the gallery.
     *
     * @param string $albumId
     * @return \Imgur\Api\Model\Album
     */
    public function album($albumId) {
        $parameters = $this->get('gallery/album/'.$albumId);

        return new Model\Album($parameters);           
    }
    
    /**
     * Get additional information about an image in the gallery.
     *
     * @param string $imageId
     * @return \Imgur\Api\Model\Image
     */
    public function image($imageId) {
        $parameters = $this->get('gallery/image/'.$imageId);

        return new Model\Image($parameters);           
    }
    
    /**
     * Report an Image in the gallery
     *
     * @param string $imageOrAlbumId
     * @return \Imgur\Api\Model\Vote
     */
    public function report($imageOrAlbumId) {
        $parameters = $this->post('gallery/'.$imageOrAlbumId.'/report');

        return new Model\Vote($parameters);           
    }
    
    /**
     * Get the vote information about an image or album
     * 
     * @param string $imageOrAlbumId
     * @return \Imgur\Api\Model\Vote
     */
    public function votes($imageOrAlbumId) {
        $parameters = $this->get('gallery/'.$imageOrAlbumId.'/votes');

        return new Model\Vote($parameters);           
    }
    
    /**
     * Vote for an image, 'up' or 'down' vote. Send the same value again to undo a vote.
     * 
     * @param string $imageOrAlbumId
     * @param string $vote (up|down)
     * @return \Imgur\Api\Model\Basic
     */
    public function vote($imageOrAlbumId, $vote) {
        $parameters = $this->get('gallery/'.$imageOrAlbumId.'/vote/'.$vote);

        return new Model\Basic($parameters);          
    }
    
    /**
     * Retrieve comments on an image or album in the gallery.
     * 
     * @param string $imageOrAlbumId
     * @param string $sort (best | top | new)
     * @return \Imgur\Api\Model\Comment|array
     */
    public function comments($imageOrAlbumId, $sort = 'best') {
        $parameters = $this->get('gallery/'.$imageOrAlbumId.'/comments/'.$sort);
        
        $comments = array();
        
        foreach($parameters['data'] as $parameter) {
            $comments[] = new Model\Comment($parameter);
        }
        
        return $comments;
    }
    
    /**
     * Information about a specific comment.
     * 
     * @param string $imageOrAlbumId
     * @param string $commentId
     * @return \Imgur\Api\Model\Comment
     */
    public function comment($imageOrAlbumId, $commentId) {
        $parameters = $this->get('gallery/'.$imageOrAlbumId.'/comment/'.$commentId);
        
        return new Model\Comment($parameters);
    }
    
    /**
     * Create a comment for an image/album.
     * 
     * @param string $imageOrAlbumId
     * @param array $data
     * @link https://api.imgur.com/endpoints/gallery#gallery-comment-creation
     * @return \Imgur\Api\Model\Basic
     */
    public function createComment($imageOrAlbumId, $data) {
        $parameters = $this->post('gallery/'.$imageOrAlbumId.'/comment', $data);

        return new Model\Basic($parameters);          
    }
    
    /**
     * List all of the IDs for the comments on an image/album.
     * 
     * @param string $imageOrAlbumId
     * @return \Imgur\Api\Model\Basic
     */
    public function commentIds($imageOrAlbumId) {
        $parameters = $this->get('gallery/'.$imageOrAlbumId.'/comments/ids');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * The number of comments on an Image.
     * 
     * @param string $imageOrAlbumId
     * @return \Imgur\Api\Model\Basic
     */
    public function commentCount($imageOrAlbumId) {
        $parameters = $this->get('gallery/'.$imageOrAlbumId.'/comments/count');
        
        return new Model\Basic($parameters);        
    }
    
    /**
     * Parses an array of data and creates the appropriate objects
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\GalleryImage or \Imgur\Api\Model\GalleryAlbum | array
     */
    private function createAlbumOrImageObjects($parameters) {
        $images = array();

        foreach($parameters['data'] as $parameter) {
            if(!empty($parameter['is_album'])) {
                $images[] = new Model\GalleryAlbum($parameter);
            }
            else {
                $images[] = new Model\GalleryImage($parameter);
            }
        }           
        
        return $images;
    }
}