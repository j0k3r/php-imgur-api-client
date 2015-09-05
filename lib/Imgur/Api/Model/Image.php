<?php

namespace Imgur\Api\Model;

/**
 * Model for Image.
 *
 * @link https://api.imgur.com/models/image
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Image
{
    /**
     * The ID for the image.
     *
     * @var string
     */
    private $id;

    /**
     * The title of the image.
     *
     * @var string
     */
    private $title;

    /**
     * Description of the image.
     *
     * @var string
     */
    private $description;

    /**
     * Time inserted into the gallery, epoch time.
     *
     * @var int
     */
    private $datetime;

    /**
     * Image MIME type.
     *
     * @var string
     */
    private $type;

    /**
     * Is the image animated.
     *
     * @var bool
     */
    private $animated;

    /**
     * The width of the image in pixels.
     *
     * @var int
     */
    private $width;

    /**
     * The width of the image in pixels.
     *
     * @var int
     */
    private $height;

    /**
     * The size of the image in bytes.
     *
     * @var size
     */
    private $size;

    /**
     * The number of image views.
     *
     * @var int
     */
    private $views;

    /**
     * Bandwidth consumed by the image in bytes.
     *
     * @var int
     */
    private $bandwidth;

    /**
     * OPTIONAL, the deletehash, if you're logged in as the image owner.
     *
     * @var string
     */
    private $deletehash;

    /**
     * If the image has been categorized by the Imgur backend then this will contain the section the image belongs in. (funny, cats, adviceanimals, wtf, etc).
     *
     * @var type
     */
    private $section;

    /**
     * The direct link to the the image.
     *
     * @var type
     */
    private $link;

    /**
     * OPTIONAL, the original filename, if you're logged in as the image owner.
     *
     * @var string
     */
    private $name;

    /**
     * OPTIONAL, The .gifv link. Only available if the image is animated and type is 'image/gif'.
     *
     * @var string
     */
    private $gifv;

    /**
     * OPTIONAL, The direct link to the .mp4. Only available if the image is animated and type is 'image/gif'.
     *
     * @var string
     */
    private $mp4;

    /**
     * OPTIONAL, The direct link to the .webm. Only available if the image is animated and type is 'image/gif'.
     *
     * @var string
     */
    private $webm;

    /**
     * OPTIONAL, Whether the image has a looping animation. Only available if the image is animated and type is 'image/gif'.
     *
     * @var bool
     */
    private $looping;

    /**
     * Indicates if the current user favorited the image. Defaults to false if not signed in.
     *
     * @var bool
     */
    private $favorite;

    /**
     * Indicates if the image has been marked as nsfw or not. Defaults to null if information is not available.
     *
     * @var bool
     */
    private $nsfw;

    /**
     * The current user's vote on the album. null if not signed in, if the user hasn't voted on it, or if not submitted to the gallery.
     *
     * @var string
     */
    private $vote;

    /**
     * Build the Image object based on an array.
     *
     * @param array $parameters
     *
     * @return \Imgur\Api\Model\Image
     */
    public function __construct($parameters)
    {
        if (!empty($parameters['data'])) {
            $parameters = $parameters['data'];
        }

        $this->setId($parameters['id'])
             ->setTitle($parameters['title'])
             ->setDescription($parameters['description'])
             ->setDatetime($parameters['datetime'])
             ->setType($parameters['description'])
             ->setAnimated($parameters['animated'])
             ->setWidth($parameters['width'])
             ->setHeight($parameters['height'])
             ->setSize($parameters['size'])
             ->setViews($parameters['views'])
             ->setBandwidth($parameters['bandwidth'])
             ->setSection($parameters['section'])
             ->setLink($parameters['link'])
             ->setName($parameters['name'])
             ->setGifv($parameters['gifv'])
             ->setMp4($parameters['mp4'])
             ->setWebm($parameters['webm'])
             ->setLooping($parameters['looping'])
             ->setFavorite($parameters['favorite'])
             ->setNsfw($parameters['nsfw'])
             ->setVote($parameters['vote']);

        if (!empty($parameters['deletehash'])) {
            $this->setDeletehash($parameters['deletehash']);
        }

        return $this;
    }

    /**
     * The account id for the username requested.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * The account id for the username requested.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The title of the image.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * The title of the image.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * The description of the image.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * The description of the image.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Time inserted into the gallery, epoch time.
     *
     * @param int $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Time inserted into the gallery, epoch time.
     *
     * @return int|null
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Image MIME type.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Image MIME type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Is the image animated.
     *
     * @param bool $animated
     */
    public function setAnimated($animated)
    {
        $this->animated = $animated;

        return $this;
    }

    /**
     * Is the image animated.
     *
     * @return bool|null
     */
    public function getAnimated()
    {
        return $this->animated;
    }

    /**
     * Width of the image in pixels.
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Width of the image in pixels.
     *
     * @return int|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Height of the image in pixels.
     *
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Height of the image in pixels.
     *
     * @return int|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * The size of the image in bytes.
     *
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * The size of the image in bytes.
     *
     * @return int|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * The number of image views.
     *
     * @param int $views
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * The number of image views.
     *
     * @return int|null
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Bandwidth consumed by the image in bytes.
     *
     * @param int $bandwidth
     */
    public function setBandwidth($bandwidth)
    {
        $this->bandwidth = $bandwidth;

        return $this;
    }

    /**
     * Bandwidth consumed by the image in bytes.
     *
     * @return int|null
     */
    public function getBandwidth()
    {
        return $this->bandwidth;
    }

    /**
     * The deletehash.
     *
     * @param string $deletehash
     */
    public function setDeletehash($deletehash)
    {
        $this->deletehash = $deletehash;

        return $this;
    }

    /**
     * The deletehash.
     *
     * @return string|null
     */
    public function getDeletehash()
    {
        return $this->deletehash;
    }

    /**
     * If the image has been categorized by the Imgur backend then this will contain the section the image belongs in. (funny, cats, adviceanimals, wtf, etc).
     *
     * @param string $section
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * If the image has been categorized by the Imgur backend then this will contain the section the image belongs in. (funny, cats, adviceanimals, wtf, etc).
     *
     * @return string|null
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * The direct link to the the image.
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * The direct link to the the image.
     *
     * @return string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * OPTIONAL, the original filename, if you're logged in as the image owner.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * OPTIONAL, the original filename, if you're logged in as the image owner.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * OPTIONAL, The .gifv link. Only available if the image is animated and type is 'image/gif'.
     *
     * @param string $gifv
     */
    public function setGifv($gifv)
    {
        $this->gifv = $gifv;

        return $this;
    }

    /**
     * OPTIONAL, The .gifv link. Only available if the image is animated and type is 'image/gif'.
     *
     * @return string $gifv
     */
    public function getGifv()
    {
        return $this->gifv;
    }

    /**
     * OPTIONAL, The direct link to the .mp4. Only available if the image is animated and type is 'image/gif'.
     *
     * @param string $mp4
     */
    public function setMp4($mp4)
    {
        $this->mp4 = $mp4;

        return $this;
    }

    /**
     * OPTIONAL, The direct link to the .mp4. Only available if the image is animated and type is 'image/gif'.
     *
     * @return string $mp4
     */
    public function getMp4()
    {
        return $this->mp4;
    }

    /**
     * OPTIONAL, The direct link to the .webm. Only available if the image is animated and type is 'image/gif'.
     *
     * @param string $webm
     */
    public function setWebm($webm)
    {
        $this->webm = $webm;

        return $this;
    }

    /**
     * OPTIONAL, The direct link to the .webm. Only available if the image is animated and type is 'image/gif'.
     *
     * @return string $webm
     */
    public function getWebm()
    {
        return $this->webm;
    }

    /**
     * OPTIONAL, Whether the image has a looping animation. Only available if the image is animated and type is 'image/gif'.
     *
     * @param bool $looping
     */
    public function setLooping($looping)
    {
        $this->looping = $looping;

        return $this;
    }

    /**
     * OPTIONAL, Whether the image has a looping animation. Only available if the image is animated and type is 'image/gif'.
     *
     * @return bool $looping
     */
    public function getLooping()
    {
        return $this->looping;
    }

    /**
     * Indicates if the current user favorited the image. Defaults to false if not signed in.
     *
     * @param bool $favorite
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * Indicates if the current user favorited the image. Defaults to false if not signed in.
     *
     * @return bool $favorite
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Indicates if the image has been marked as nsfw or not. Defaults to null if information is not available.
     *
     * @param bool $nsfw
     */
    public function setNsfw($nsfw)
    {
        $this->nsfw = $nsfw;

        return $this;
    }

    /**
     * Indicates if the image has been marked as nsfw or not. Defaults to null if information is not available.
     *
     * @return bool $nsfw
     */
    public function getNsfw()
    {
        return $this->nsfw;
    }

    /**
     * The current user's vote on the album. null if not signed in, if the user hasn't voted on it, or if not submitted to the gallery.
     *
     * @param string $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * The current user's vote on the album. null if not signed in, if the user hasn't voted on it, or if not submitted to the gallery.
     *
     * @return string $vote
     */
    public function getVote()
    {
        return $this->vote;
    }
}
