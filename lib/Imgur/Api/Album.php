<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Albums
 * 
 * @link https://api.imgur.com/endpoints/album
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Album extends AbstractApi {
    /**
     * Get information about a specific album.
     * 
     * @param string $albumId
     * @return \Imgur\Api\Model\Album
     */
    public function album($albumId) {
        $parameters = $this->get('album/'.$albumId);
        
        return new Model\Album($parameters);        
    }
    
    /**
     * Return all of the images in the album
     * 
     * @param string $albumId
     * @return \Imgur\Api\Model\Image|array
     */
    public function albumImages($albumId) {
        $parameters = $this->get('album/'.$albumId.'/images');
        
        $images = array();
        
        foreach($parameters['data'] as $parameter) {
            $images[] = new Model\Image($parameter);
        }
        
        return $images;
    }
    
    /**
     * Get information about an image in an album, any additional actions found in Image Endpoint will also work.
     * 
     * @param string $albumId
     * @param string $imageId
     * @return \Imgur\Api\Model\Image
     */
    public function albumImage($albumId, $imageId) {
        $parameters = $this->get('album/'.$albumId.'/image/'.$imageId);
        
        return new Model\Image($parameters);
    }
    
    /**
     * Create a new album. Optional parameter of ids[] is an array of image ids to add to the album (if you're authenticated with an account).
     * This method is available without authenticating an account, and may be used merely by sending "Authorization: Client-ID {client_id}" 
     * in the request headers. Doing so will create an anonymous album which is not tied to an account. 
     * Adding images to an anonymous album is only available during image uploading.
     * 
     * @param array $data
     * @return \Imgur\Api\Model\Basic
     */
    public function create($data) {
        $parameters = $this->post('album', $data);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Update the information of an album. 
     * For anonymous albums, {album} should be the deletehash that is returned at creation.
     * 
     * @param string $deletehashOrAlbumId
     * @param array $data
     * @return \Imgur\Api\Model\Basic
     */
    public function update($deletehashOrAlbumId, $data) {
        $parameters = $this->post('album/'.$deletehashOrAlbumId, $data); 
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Delete an album with a given ID. You are required to be logged in as the user to delete the album. 
     * Takes parameter, ids[], as an array of ids and removes from the labum. 
     * For anonymous albums, {album} should be the deletehash that is returned at creation.
     * 
     * @param string $deletehashOrAlbumId
     * @return \Imgur\Api\Model\Basic
     */
    public function deleteAlbum($deletehashOrAlbumId) {
        $parameters = $this->delete('album/'.$deletehashOrAlbumId);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Favorite an album with a given ID. The user is required to be logged in to favorite the album.
     * 
     * @param string $albumId
     * @return \Imgur\Api\Model\Basic
     */
    public function favoriteAlbum($albumId) {
        $parameters = $this->post('album/'.$albumId.'/favorite');
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Sets the images for an album, removes all other images and only uses the images in this request. 
     * (Not available for anonymous albums.)
     * 
     * @param string $albumId
     * @param array $imageIds
     * @return \Imgur\Api\Model\Basic
     */
    public function setAlbumImages($albumId, $imageIds) {
        $parameters = $this->post('album/'.$albumId, array('ids' => implode(',', $imageIds)));
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Takes parameter, ids[], as an array of ids to add to the album.
     * (Not available for anonymous albums. Adding images to an anonymous album is only available during image uploading.)
     * 
     * @param string $albumId
     * @param array $imageIds
     * @return \Imgur\Api\Model\Basic
     */
    public function addImages($albumId, $imageIds) {
        $parameters = $this->post('album/'.$albumId.'/add', array('ids' => implode(',', $imageIds)));
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Takes parameter, ids[], as an array of ids and removes from the album.
     * For anonymous albums, $deletehashOrAlbumId should be the deletehash that is returned at creation.
     * 
     * @param string $deletehashOrAlbumId
     * @param array $imageIds
     * @return \Imgur\Api\Model\Basic
     */
    public function removeImages($deletehashOrAlbumId, $imageIds) {
        $parameters = $this->delete('album/'.$deletehashOrAlbumId.'/remove_images', array('ids' => implode(',', $imageIds)));
        
        return new Model\Basic($parameters);
    }
}