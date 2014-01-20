<?php

namespace Imgur\Api\Model;

/**
 * Model for Trophy
 * 
 * @link https://api.imgur.com/models/gallery_profile
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */

class Trophy {
    
    /**
     * The ID of the trophy, this is unique to each trophy
     * 
     * @var integer
     */
    private $id;
    
    /**
     * The name of the trophy
     * 
     * @var string
     */
    private $name;
    
    /**
     * Can be thought of as the ID of a trophy type
     *
     * @var string
     */
    private $nameClean;
    
    /**
     * A description of the trophy and how it was earned.
     * 
     * @var string
     */
    private $description;
    
    /**
     * The ID of the image or the ID of the comment where the trophy was earned
     * 
     * @var string
     */
    private $data;
    
    /**
     * A link to where the trophy was earned
     * 
     * @var string
     */
    private $dataLink;
    
    /**
     * Date the trophy was earned, epoch time
     * 
     * @var integer
     */
    private $datetime;
    
    /**
     * Build the Trophy object from an array
     * 
     * @param array $parameters
     * @return \Imgur\Api\Model\Trophy
     */
    public function __construct($parameters) {
        $this->setData($parameters['data']) 
             ->setDataLink($parameters['data_link'])
             ->setDatetime($parameters['datetime'])
             ->setDescription($parameters['description'])
             ->setId($parameters['id'])
             ->setName($parameters['name'])
             ->setNameClean($parameters['name_clean']);
        
        return $this;
    }
    
    /**
     * The ID of the trophy, this is unique to each trophy
     * 
     * @return integer
     */
    public function getId() {
        
        return $this->id;
    }

    /**
     * The ID of the trophy, this is unique to each trophy
     * 
     * @param integer $id
     * @return \Imgur\Api\Model\Trophy
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * The name of the trophy
     * 
     * @return string
     */
    public function getName() {
        
        return $this->name;
    }

    /**
     * The name of the trophy
     * 
     * @param string $name
     * @return \Imgur\Api\Model\Trophy
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;        
    }

    /**
     * Can be thought of as the ID of a trophy type
     * 
     * @return string
     */
    public function getNameClean() {
        
        return $this->nameClean;
    }

    /**
     * Can be thought of as the ID of a trophy type
     * 
     * @param string $nameClean
     * @return \Imgur\Api\Model\Trophy
     */
    public function setNameClean($nameClean) {
        $this->nameClean = $nameClean;
        
        return $this;        
    }

    /**
     * A description of the trophy and how it was earned.
     * 
     * @return string
     */
    public function getDescription() {
        
        return $this->description;
    }

    /**
     * A description of the trophy and how it was earned.
     * 
     * @param string $description
     * @return \Imgur\Api\Model\Trophy
     */
    public function setDescription($description) {
        $this->description = $description;
        
        return $this;        
    }

    /**
     * The ID of the image or the ID of the comment where the trophy was earned
     * 
     * @return type
     */
    public function getData() {
        
        return $this->data;
    }

    /**
     * The ID of the image or the ID of the comment where the trophy was earned
     * 
     * @param string $data
     * @return \Imgur\Api\Model\Trophy
     */
    public function setData($data) {
        $this->data = $data;
        
        return $this;        
    }

    /**
     * A link to where the trophy was earned
     * 
     * @return string
     */
    public function getDataLink() {
        
        return $this->dataLink;
    }

    /**
     * A link to where the trophy was earned
     * 
     * @param string $dataLink
     * @return \Imgur\Api\Model\Trophy
     */
    public function setDataLink($dataLink) {
        $this->dataLink = $dataLink;
        
        return $this;        
    }

    /**
     * Date the trophy was earned, epoch time
     * 
     * @return integer
     */
    public function getDatetime() {
        
        return $this->datetime;
    }

    /**
     * Date the trophy was earned, epoch time
     * 
     * @param integer $datetime
     * @return \Imgur\Api\Model\Trophy
     */
    public function setDatetime($datetime) {
        $this->datetime = $datetime;
        
        return $this;        
    }    
}