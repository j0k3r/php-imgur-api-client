<?php

namespace Imgur\Api;

/**
 * CRUD for Albums.
 *
 * @see https://api.imgur.com/endpoints/album
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Album extends AbstractApi
{
    /**
     * Get information about a specific album.
     *
     * @param string $albumId
     *
     * @see https://api.imgur.com/endpoints/album#album
     *
     * @return array Album (@see https://api.imgur.com/models/album)
     */
    public function album($albumId)
    {
        return $this->get('album/' . $albumId);
    }

    /**
     * Return all of the images in the album.
     *
     * @param string $albumId
     *
     * @see https://api.imgur.com/endpoints/album#album-images
     *
     * @return array Array of Image (@see https://api.imgur.com/models/image)
     */
    public function albumImages($albumId)
    {
        return $this->get('album/' . $albumId . '/images');
    }

    /**
     * Get information about an image in an album, any additional actions found in Image Endpoint will also work.
     *
     * @param string $albumId
     * @param string $imageId
     *
     * @see https://api.imgur.com/endpoints/album#album-image
     *
     * @return array Image (@see https://api.imgur.com/models/image)
     */
    public function albumImage($albumId, $imageId)
    {
        return $this->get('album/' . $albumId . '/image/' . $imageId);
    }

    /**
     * Create a new album. Optional parameter of ids[] is an array of image ids to add to the album (if you're authenticated with an account).
     * This method is available without authenticating an account, and may be used merely by sending "Authorization: Client-ID {client_id}"
     * in the request headers. Doing so will create an anonymous album which is not tied to an account.
     * Adding images to an anonymous album is only available during image uploading.
     *
     * @param array $data
     *
     * @see https://api.imgur.com/endpoints/album#album-upload
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function create($data)
    {
        return $this->post('album', $data);
    }

    /**
     * Update the information of an album.
     * For anonymous albums, {album} should be the deletehash that is returned at creation.
     *
     * @param string $deletehashOrAlbumId
     * @param array  $data
     *
     * @see https://api.imgur.com/endpoints/album#album-update
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function update($deletehashOrAlbumId, $data)
    {
        return $this->post('album/' . $deletehashOrAlbumId, $data);
    }

    /**
     * Delete an album with a given ID. You are required to be logged in as the user to delete the album.
     * Takes parameter, ids[], as an array of ids and removes from the labum.
     * For anonymous albums, {album} should be the deletehash that is returned at creation.
     *
     * @param string $deletehashOrAlbumId
     *
     * @see https://api.imgur.com/endpoints/album#album-delete
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function deleteAlbum($deletehashOrAlbumId)
    {
        return $this->delete('album/' . $deletehashOrAlbumId);
    }

    /**
     * Favorite an album with a given ID. The user is required to be logged in to favorite the album.
     *
     * @param string $albumId
     *
     * @see https://api.imgur.com/endpoints/album#album-favorite
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function favoriteAlbum($albumId)
    {
        return $this->post('album/' . $albumId . '/favorite');
    }

    /**
     * Sets the images for an album, removes all other images and only uses the images in this request.
     * (Not available for anonymous albums.).
     *
     * @param string $albumId
     *
     * @see https://api.imgur.com/endpoints/album#album-set-to
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function setAlbumImages($albumId, array $imageIds)
    {
        return $this->post('album/' . $albumId, ['ids' => implode(',', $imageIds)]);
    }

    /**
     * Takes parameter, ids[], as an array of ids to add to the album.
     * (Not available for anonymous albums. Adding images to an anonymous album is only available during image uploading.).
     *
     * @param string $albumId
     *
     * @see https://api.imgur.com/endpoints/album#album-add-to
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function addImages($albumId, array $imageIds)
    {
        return $this->post('album/' . $albumId . '/add', ['ids' => implode(',', $imageIds)]);
    }

    /**
     * Takes parameter, ids[], as an array of ids and removes from the album.
     * For anonymous albums, $deletehashOrAlbumId should be the deletehash that is returned at creation.
     *
     * @param string $deletehashOrAlbumId
     *
     * @see https://api.imgur.com/endpoints/album#album-remove-from
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function removeImages($deletehashOrAlbumId, array $imageIds)
    {
        return $this->delete('album/' . $deletehashOrAlbumId . '/remove_images', ['ids' => implode(',', $imageIds)]);
    }
}
