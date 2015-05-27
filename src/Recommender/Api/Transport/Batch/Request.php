<?php
/**
 * Class responsible of Modgen API batch calls
 * @package Recommender/Api/Helpers
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @version 1.0
 * @since 2015-05-25
 * @license
 */

namespace Recommender\Api\Transport\Batch;

class Request
{
    /*
     * @var array - array of elements which will be added in to POST
     */
    private $params;

    /*
     * @var string - define query method
     */
    private $method = 'GET';

    /*
     * @var string - define query path
     */
    private $path = '';

    /*
     * @var integer - define params count
     */
    private $count = 0;

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @param int $increase
     */
    public function increaseCount($increase = 1)
    {
        $this->setCount($this->getCount() + $increase);
    }

    /**
     * Method will add param key and its value
     * @param string $method - request metod GET/POST/PUT, etc
     * @param string $path - url path to call
     */
    public function __construct($method, $path)
    {
        $this->setMethod($method);
        $this->setPath($path);
    }

    /**
     * Method will return JSON request object
     * @return string
     */
    public function getRequest()
    {
        $o = get_object_vars($this);
        if( count($o['params']) === 0 ){
            unset($o['params']);
        }
        unset($o['count']);
        return $o;
    }
}