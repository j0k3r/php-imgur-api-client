<?php

namespace Imgur\Api;

/**
 * CRUD for CustomGallery.
 *
 * @see https://api.imgur.com/endpoints/custom_gallery
 */
class CustomGallery extends AbstractApi
{
    /**
     * View images for current user's custom gallery.
     *
     * @param string $sort   (viral | top | time)
     * @param int    $page
     * @param string $window (day | week | month | year | all)
     *
     * @see https://api.imgur.com/endpoints/custom_gallery#custom-gallery
     *
     * @return array Custom Gallery (@see https://api.imgur.com/models/custom_gallery)
     */
    public function customGallery($sort = 'viral', $page = 0, $window = 'week')
    {
        $this->validateSortArgument($sort, ['viral', 'top', 'time']);
        $this->validateWindowArgument($window, ['day', 'week', 'month', 'year', 'all']);

        return $this->get('g/custom/' . $sort . '/' . $window . '/' . (int) $page);
    }

    /**
     * Retrieve user's filtered out gallery.
     *
     * @param string $sort   (viral | top | time)
     * @param int    $page
     * @param string $window (day | week | month | year | all)
     *
     * @see https://api.imgur.com/endpoints/custom_gallery#filtered-out-gallery
     *
     * @return array Custom Gallery (@see https://api.imgur.com/models/custom_gallery)
     */
    public function filtered($sort = 'viral', $page = 0, $window = 'week')
    {
        $this->validateSortArgument($sort, ['viral', 'top', 'time']);
        $this->validateWindowArgument($window, ['day', 'week', 'month', 'year', 'all']);

        return $this->get('g/filtered/' . $sort . '/' . $window . '/' . (int) $page);
    }

    /**
     * View a single image in a user's custom gallery.
     *
     * @param string $imageId The ID for the gallery item
     *
     * @see https://api.imgur.com/endpoints/custom_gallery#custom-gallery-image
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function image($imageId)
    {
        return $this->get('g/custom/' . $imageId);
    }

    /**
     * Add tags to a user's custom gallery.
     *
     * @see https://api.imgur.com/endpoints/custom_gallery#custom-gallery-add
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function addTags(array $tags)
    {
        return $this->put('g/add_tags', ['tags' => implode(',', $tags)]);
    }

    /**
     * Remove tags from a custom gallery.
     *
     * @see https://api.imgur.com/endpoints/custom_gallery#custom-gallery-remove
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function removeTags(array $tags)
    {
        return $this->delete('g/remove_tags', ['tags' => implode(',', $tags)]);
    }

    /**
     * Filter out a tag.
     *
     * @param string $tag
     *
     * @see https://api.imgur.com/endpoints/custom_gallery#filtered-out-block
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function blockTag($tag)
    {
        return $this->post('g/block_tag', ['tag' => $tag]);
    }

    /**
     * Remove a filtered out tag.
     *
     * @param string $tag
     *
     * @see https://api.imgur.com/endpoints/custom_gallery#filtered-out-unblock
     *
     * @return array (@see https://api.imgur.com/models/basic)
     */
    public function unBlockTag($tag)
    {
        return $this->post('g/unblock_tag', ['tag' => $tag]);
    }
}
