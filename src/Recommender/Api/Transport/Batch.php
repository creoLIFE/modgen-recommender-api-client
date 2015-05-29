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

namespace Recommender\Api\Transport;

use Recommender\Api\Transport\Batch\Request;
use Recommender\Api\Transport\Transport;

class Batch extends Transport
{
    /*
     * @var string - ADD Item URL
    */
    const API_URL_BATCH = '/batch/';

    /*
     * @var array - batch elements
     */
    private $batch = array();

    /*
     * @var integer - define number of elements which are available in batch post data
     */
    private $batchSize = 1000;

    /*
     * @var integer - define params count
     */
    private $count = 0;

    /*
     * @var string - Batch request type
     */
    private $bathMethod = 'POST';

    /**
     * @return array
     */
    public function getBatch()
    {
        $request = array(
            'requests' => $this->batch
        );
        return $request;
    }

    /**
     * @param array $batch
     */
    public function setBatch($batch)
    {
        $this->batch = $batch;
    }

    /**
     * @return int
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * @param int $batchSize
     */
    public function setBatchSize($batchSize)
    {
        $this->batchSize = $batchSize;
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
     * @return string
     */
    public function getBathMethod()
    {
        return $this->bathMethod;
    }

    /**
     * @param string $bathMethod
     */
    public function setBathMethod($bathMethod)
    {
        $this->bathMethod = $bathMethod;
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
     * @param array $data - object with data
     * @param string $postType - type of post data
     * @return mixed
     */
    public function addCall($method, $path, array $data = array(), $postType = 'JSON')
    {
        $request = new Request($method, $path);
        $request->setParams($data);
        $this->increaseCount();
        $this->setPostType( strtolower($postType) === 'get' ? 'batch' : $postType);

        $this->batch[] = $request->getRequest();

        if ($this->getCount() >= $this->getBatchSize()) {
            self::process();
        }
    }

    /**
     * Method will perform batch call
     */
    public function process()
    {
        parent::addCall($this->getBathMethod(), self::API_URL_BATCH, $this->getBatch(), $this->getPostType());
        $result = parent::process();

        //echo "<pre>";
        //print_r($this->getBatch());

        $this->setBatch(array());
        $this->setCount(0);

        return $result;
    }
}