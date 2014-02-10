<?php

namespace Imgur\Api;

use Imgur\Api\AbstractApi;

/**
 * CRUD for Memegen
 * 
 * @link https://api.imgur.com/endpoints/memegen
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Memegen extends AbstractApi {
    
    /**
     * Get the list of default memes.
     * 
     * @return \Imgur\Api\Model\Image|array
     */
    public function defaultMemes() {
        $parameters = $this->get('memegen/defaults');
        
        $images = array();
        
        foreach($parameters['data'] as $parameter) {
            $images[] = new Model\Image($parameter);
        }
        
        return $images;
    }
}