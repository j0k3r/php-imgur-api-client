<?php

namespace Imgur\Api\Model;

/**
 * Model for Gallery Image.
 *
 * @link https://api.imgur.com/models/gallery_image
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class GalleryImage extends Image
{
    /**
     * The username of the account that uploaded it, or null.
     *
     * @var string
     */
    private $accountUrl;

    /**
     * Upvotes for the image.
     *
     * @var int
     */
    private $ups;

    /**
     * Number of downvotes for the image.
     *
     * @var int
     */
    private $downs;

    /**
     * Imgur popularity score.
     *
     * @var int
     */
    private $score;

    /**
     * If it's an album or not.
     *
     * @var bool
     */
    private $isAlbum;

    /**
     * Build the GalleryImage object based on an array.
     *
     * @param array $parameters
     *
     * @return \Imgur\Api\Model\GalleryImage
     */
    public function __construct($parameters)
    {
        parent::__construct($parameters);

        $this->setAccountUrl($parameters['account_url'])
             ->setUps($parameters['ups'])
             ->setDowns($parameters['downs'])
             ->setScore($parameters['score'])
             ->setIsAlbum($parameters['is_album']);

        return $this;
    }

    /**
     * The username of the account that uploaded it, or null.
     *
     * @param string $accountUrl
     */
    public function setAccountUrl($accountUrl)
    {
        $this->accountUrl = $accountUrl;

        return $this;
    }

    /**
     * The username of the account that uploaded it, or null.
     *
     * @return string $accountUrl
     */
    public function getAccountUrl()
    {
        return $this->accountUrl;
    }

    /**
     * Upvotes for the image.
     *
     * @param int $ups
     */
    public function setUps($ups)
    {
        $this->ups = $ups;

        return $this;
    }

    /**
     * Upvotes for the image.
     *
     * @return int $ups
     */
    public function getUps()
    {
        return $this->ups;
    }

    /**
     * Downvotes for the image.
     *
     * @param int $downs
     */
    public function setDowns($downs)
    {
        $this->downs = $downs;

        return $this;
    }

    /**
     * Downvotes for the image.
     *
     * @return int $downs
     */
    public function getDowns()
    {
        return $this->downs;
    }

    /**
     * Imgur popularity score.
     *
     * @param int $score
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Imgur popularity score.
     *
     * @return int $score
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * If it's an album or not.
     *
     * @param bool $isAlbum
     */
    public function setIsAlbum($isAlbum)
    {
        $this->isAlbum = $isAlbum;

        return $this;
    }

    /**
     * If it's an album or not.
     *
     * @return bool $isAlbum
     */
    public function getIsAlbum()
    {
        return $this->isAlbum;
    }
}
