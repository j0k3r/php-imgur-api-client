<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Images
 * 
 * @link https://api.imgur.com/endpoints/image
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Image extends AbstractApi {
    
    /**
     * Upload a new image.
     * 
     * @param array $data
     * @link https://api.imgur.com/endpoints/image#image-upload
     * @return \Imgur\Api\Model\Basic
     */
    public function upload($data) {
        if($data['type'] == 'file') {
            $data['image'] = '@'.$data['image'];
        }
        
        $parameters = $this->post('image', $data);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Get information about an image.
     * 
     * @param string $imageId
     * @return \Imgur\Api\Model\Image
     */
    public function image($imageId) {
        $parameters = $this->get('image/'.$imageId);
        
        return new Model\Image($parameters);
    }
    
    /**
     * Deletes an image. For an anonymous image, $imageIdOrDeleteHash must be the image's deletehash. 
     * If the image belongs to your account then passing the ID of the image is sufficient.
     * 
     * @param string $imageIdOrDeleteHash
     * @return \Imgur\Api\Model\Basic
     */
    public function deleteImage($imageIdOrDeleteHash) {
        $parameters = $this->delete('image/'.$imageIdOrDeleteHash);
        
        return new Model\Basic($parameters);        
    }
    
    /**
     * Updates the title or description of an image. 
     * You can only update an image you own and is associated with your account. 
     * For an anonymous image, {id} must be the image's deletehash.
     * 
     * @param string $imageIdOrDeleteHash
     * @param array $data
     * @link https://api.imgur.com/endpoints/image#image-update
     * @return \Imgur\Api\Model\Basic
     */
    public function update($imageIdOrDeleteHash, $data) {
        $parameters = $this->post('image/'.$imageIdOrDeleteHash, $data);
        
        return new Model\Basic($parameters);
    }
    
    /**
     * Favorite an image with the given ID. The user is required to be logged in to favorite the image.
     * 
     * @param string $imageIdOrDeleteHash
     * @return \Imgur\Api\Model\Basic
     */
    public function favorite($imageIdOrDeleteHash) {
        $parameters = $this->post('image/'.$imageIdOrDeleteHash.'/favorite');
        
        return new Model\Basic($parameters);        
    }
}