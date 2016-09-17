<?php

namespace Imgur\Api\Model;

/**
 * Basic Model.
 *
 * @link https://api.imgur.com/models/basic
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class Basic
{
    /**
     * Is null, boolean, or integer value. If it's a post then this will contain
     * an object with the all generated values, such as an ID.
     *
     * @var null|bool|int
     */
    private $data;

    /**
     * Was the request successful.
     *
     * @var bool
     */
    private $success;

    /**
     * HTTP Status Code.
     *
     * @var int
     */
    private $status;

    public function __construct($parameters)
    {
        $this->setData($parameters['data'])
             ->setSuccess($parameters['success'])
             ->setStatus($parameters['status']);
    }

    /**
     * Is null, boolean, or integer value. If it's a post then this will contain
     * an object with the all generated values, such as an ID.
     *
     * @var null|bool|int
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Is null, boolean, or integer value. If it's a post then this will contain
     * an object with the all generated values, such as an ID.
     *
     * @return null|bool|int
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Was the request successful.
     *
     * @var bool
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Was the request successful.
     *
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * HTTP Status Code.
     *
     * @var int
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * HTTP Status Code.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
}
