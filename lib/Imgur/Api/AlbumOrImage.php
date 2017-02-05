<?php

namespace Imgur\Api;

use Imgur\Exception\ErrorException;
use Imgur\Exception\ExceptionInterface;

/**
 * This is a special endpoint.
 * When you got an Imgur link it's almost impossible to be 100% sure if it's an image or an album.
 * This endpoint aim to fix that by first checking an id as an image and if it's fail, test it as an album.
 */
class AlbumOrImage extends AbstractApi
{
    /**
     * Try to find an image or an album using the given parameter.
     *
     * @param string $imageIdOrAlbumId
     *
     * @return array Album (@see https://api.imgur.com/models/album) OR Image (@see https://api.imgur.com/models/image)
     */
    public function find($imageIdOrAlbumId)
    {
        try {
            return $this->get('image/' . $imageIdOrAlbumId);
        } catch (ExceptionInterface $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }
        }

        try {
            return $this->get('album/' . $imageIdOrAlbumId);
        } catch (ExceptionInterface $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }
        }

        throw new ErrorException('Unable to find an album OR an image with the id, ' . $imageIdOrAlbumId);
    }
}
