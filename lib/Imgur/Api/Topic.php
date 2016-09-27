<?php

namespace Imgur\Api;

/**
 * CRUD for Topic.
 *
 * @link https://api.imgur.com/endpoints/topic
 */
class Topic extends AbstractApi
{
    /**
     * Get the list of default topics.
     *
     * @link https://api.imgur.com/endpoints/topic#defaults
     *
     * @return array Topic (@see https://api.imgur.com/models/topic)
     */
    public function defaultTopics()
    {
        return $this->get('topics/defaults');
    }

    /**
     * View gallery items for a topic.
     *
     * @param string $topicId The ID or URL-formatted name of the topic. If using a topic's name, replace its spaces with underscores (Mother's_Day)
     * @param string $sort    (viral | top | time)
     * @param int    $page
     * @param string $window  (day | week | month | year | all)
     *
     * @link https://api.imgur.com/endpoints/topic#gallery-topic
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function galleryTopic($topicId, $sort = 'viral', $page = 0, $window = 'week')
    {
        $this->validateSortArgument($sort, ['viral', 'top', 'time', 'rising']);
        $this->validateWindowArgument($window, ['day', 'week', 'month', 'year', 'all']);

        return $this->get('topics/' . $topicId . '/' . $sort . '/' . $window . '/' . $page);
    }

    /**
     * View a single item in a gallery topic.
     *
     * @param string $topicId The ID or URL-formatted name of the topic. If using a topic's name, replace its spaces with underscores (Mother's_Day)
     * @param int    $itemId  The ID for the gallery item
     *
     * @link https://api.imgur.com/endpoints/topic#gallery-topic-item
     *
     * @return array Gallery Image (@see https://api.imgur.com/models/gallery_image) OR Gallery Album (@see https://api.imgur.com/models/gallery_album)
     */
    public function galleryTopicItem($topicId, $itemId)
    {
        return $this->get('topics/' . $topicId . '/' . $itemId);
    }
}
