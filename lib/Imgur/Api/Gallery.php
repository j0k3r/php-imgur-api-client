<?php

namespace Imgur\Api;

/**
 * CRUD for Gallery.
 *
 * @link https://api.imgur.com/endpoints/gallery
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Gallery extends AbstractApi
{
    /**
     * Returns the images in the gallery. For example the main gallery is https://api.imgur.com/3/gallery/hot/viral/0.json.
     *
     * @param string $section   (hot | top | user)
     * @param string $sort      (viral | top | time | rising)
     * @param int    $page
     * @param string $window    (day | week | month | year | all)
     * @param bool   $showViral
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function gallery($section = 'hot', $sort = 'viral', $page = 0, $window = 'day', $showViral = true)
    {
        return $this->get('gallery/' . $section . '/' . $sort . '/' . $window . '/' . $page, ['showViral' => var_export($showViral, true)]);
    }

    /**
     * View images for memes subgallery.
     *
     * @param string $sort   (viral | time | top)
     * @param int    $page
     * @param string $window (day | week | month | year | all)
     *
     * @link https://api.imgur.com/endpoints/gallery#meme-subgallery
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function memesSubgallery($sort = 'viral', $page = 0, $window = 'day')
    {
        return $this->get('g/memes/' . $sort . '/' . $window . '/' . $page);
    }

    /**
     * DOES NOT WORK ATM
     * View a single image in the memes gallery.
     *
     * @param string $imageId
     *
     * @link https://api.imgur.com/endpoints/gallery#meme-subgallery-image
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image)
     */
    public function memeSubgalleryImage($imageId)
    {
        return $this->get('g/memes/' . $imageId);
    }

    /**
     * View gallery images for a sub-reddit.
     *
     * @param string $subreddit (e.g pics - A valid sub-reddit name)
     * @param string $sort      (top | time)
     * @param int    $page
     * @param string $window    (day | week | month | year | all)
     *
     * @link https://api.imgur.com/endpoints/gallery#subreddit
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image)
     */
    public function subredditGalleries($subreddit, $sort = 'time', $page = 0, $window = 'day')
    {
        return $this->get('gallery/r/' . $subreddit . '/' . $sort . '/' . $window . '/' . $page);
    }

    /**
     * View a single image in the subreddit.
     *
     * @param string $subreddit (e.g pics - A valid sub-reddit name)
     * @param string $imageId
     *
     * @link https://api.imgur.com/endpoints/gallery#subreddit-image
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image)
     */
    public function subredditImage($subreddit, $imageId)
    {
        return $this->get('gallery/r/' . $subreddit . '/' . $imageId);
    }

    /**
     * Search the gallery with a given query string.
     *
     * @param string $query
     * @param string $sort  (time | viral | top)
     * @param int    $page
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-search
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function search($query, $sort = 'time', $page = 0)
    {
        return $this->get('gallery/search/' . $sort . '/' . $page, ['q' => $query]);
    }

    /**
     * Returns a random set of gallery images.
     *
     * @param int $page
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-random
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function randomGalleryImages($page = 0)
    {
        return $this->get('gallery/random/random/' . $page);
    }

    /**
     * Add an Album or Image to the Gallery.
     *
     * @param string $imageOrAlbumId
     * @param array  $data
     *
     * @link https://api.imgur.com/endpoints/gallery#to-gallery
     *
     * @return bool
     */
    public function submitToGallery($imageOrAlbumId, $data)
    {
        return $this->post('gallery/' . $imageOrAlbumId, $data);
    }

    /**
     * Remove an image from the gallery. You must be logged in as the owner of the item to do this action.
     *
     * @param string $imageOrAlbumId
     *
     * @link https://api.imgur.com/endpoints/gallery#from-gallery
     *
     * @return bool
     */
    public function removeFromGallery($imageOrAlbumId)
    {
        return $this->delete('gallery/' . $imageOrAlbumId);
    }

    /**
     * Get additional information about an album in the gallery.
     *
     * @param string $albumId
     *
     * @link https://api.imgur.com/endpoints/gallery#album
     *
     * @return array Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function album($albumId)
    {
        return $this->get('gallery/album/' . $albumId);
    }

    /**
     * Get additional information about an image in the gallery.
     *
     * @param string $imageId
     *
     * @link https://api.imgur.com/endpoints/gallery#image
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image)
     */
    public function image($imageId)
    {
        return $this->get('gallery/image/' . $imageId);
    }

    /**
     * Report an Image in the gallery.
     *
     * @param string $imageOrAlbumId
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-reporting
     *
     * @return bool
     */
    public function report($imageOrAlbumId)
    {
        return $this->post('gallery/' . $imageOrAlbumId . '/report');
    }

    /**
     * Get the vote information about an image or album.
     *
     * @param string $imageOrAlbumId
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-votes
     *
     * @return array Vote (@see https://api.imgur.com/models/vote)
     */
    public function votes($imageOrAlbumId)
    {
        return $this->get('gallery/' . $imageOrAlbumId . '/votes');
    }

    /**
     * Vote for an image, 'up' or 'down' vote. Send the same value again to undo a vote.
     *
     * @param string $imageOrAlbumId
     * @param string $vote           (up|down)
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-voting
     *
     * @return bool
     */
    public function vote($imageOrAlbumId, $vote)
    {
        return $this->post('gallery/' . $imageOrAlbumId . '/vote/' . $vote);
    }

    /**
     * Retrieve comments on an image or album in the gallery.
     *
     * @param string $imageOrAlbumId
     * @param string $sort           (best | top | new)
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-comments
     *
     * @return array Array of Comment (@see https://api.imgur.com/endpoints/gallery#gallery-comments)
     */
    public function comments($imageOrAlbumId, $sort = 'best')
    {
        return $this->get('gallery/' . $imageOrAlbumId . '/comments/' . $sort);
    }

    /**
     * Information about a specific comment.
     *
     * @param string $imageOrAlbumId
     * @param string $commentId
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-comment
     *
     * @return array Comment (@see https://api.imgur.com/endpoints/gallery#gallery-comments)
     */
    public function comment($imageOrAlbumId, $commentId)
    {
        return $this->get('gallery/' . $imageOrAlbumId . '/comment/' . $commentId);
    }

    /**
     * Create a comment for an image/album.
     *
     * @param string $imageOrAlbumId
     * @param array  $data
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-comment-creation
     *
     * @return bool
     */
    public function createComment($imageOrAlbumId, $data)
    {
        return $this->post('gallery/' . $imageOrAlbumId . '/comment', $data);
    }

    /**
     * List all of the IDs for the comments on an image/album.
     *
     * @param string $imageOrAlbumId
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-comment-ids
     *
     * @return array<int>
     */
    public function commentIds($imageOrAlbumId)
    {
        return $this->get('gallery/' . $imageOrAlbumId . '/comments/ids');
    }

    /**
     * The number of comments on an Image.
     *
     * @param string $imageOrAlbumId
     *
     * @link https://api.imgur.com/endpoints/gallery#gallery-comment-count
     *
     * @return int
     */
    public function commentCount($imageOrAlbumId)
    {
        return $this->get('gallery/' . $imageOrAlbumId . '/comments/count');
    }
}
