<?php

namespace Imgur\Api\Model;

/**
 * Model for Account Statistics
 * 
 * @link https://api.imgur.com/models/account_statistics
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class AccountStatistics {
    
    /**
     * The amount of images associated with the account
     * 
     * @var integer
     */
    private $totalImages;
    
    /**
     * The amount of albums associated with the account
     * 
     * @var integer
     */
    private $totalAlbums;
    
    /**
     * The amount of disk space used by the images
     * 
     * @var string
     */
    private $diskUsed;
    
    /**
     * The amount of bandwidth used by the account
     * 
     * @var string
     */
    private $bandwidthUsed;
    
    /**
     * The most popular Images in the account
     * 
     * @var \Imgur\Api\Model\Image|array
     */
    private $topImages;
    
    /**
     * The most popular albums in the account
     * 
     * @var \Imgur\Api\Model\Album|array
     */
    private $topAlbums;
    
    /**
     * The most popular gallery comments created by the user
     * 
     * @var \Imgur\Api\Model\Comment|array
     */
    private $topGalleryComments;
}
    