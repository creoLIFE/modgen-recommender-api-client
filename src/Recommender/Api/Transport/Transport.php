<?php
/**
 * Class responsible of Modgen API calls through CURL
 * @package Recommender/Api/Helpers
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @version 1.0
 * @since 2015-05-25
 * @license
 */

namespace Recommender\Api\Transport;

use Recommender\Api\Helpers\Curl;
use Recommender\Api\Helpers\Hmac;

class Transport
{

    /*
     * @var string - ADD Item URL
    */
    const API_URL_DBPRFIX = '/%db%';

    /*
     * @var string - Modgen API DB ID
     */
    private $db = '';

    /*
     * @var string - unique API key
     */
    private $key = '';

    /*
     * @var string - Modgen API Host
    */
    private $host = 'https://rapi.modgen.net';

    /*
     * @var string
     */
    private $method;

    /*
     * @var string
     */
    private $url;

    /*
     * @var mixed
    */
    private $postQueryData;

    /*
     * @var string
    */
    private $postType;

    /*
     * @var array
    */
    private $results;

    /**
     * @return string
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param string $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getPostQueryData()
    {
        return $this->postQueryData;
    }

    /**
     * @param mixed $postQueryData
     */
    public function setPostQueryData($postQueryData)
    {
        $this->postQueryData = $postQueryData;
    }

    /**
     * @return mixed
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * @param mixed $postType
     */
    public function setPostType($postType)
    {
        $this->postType = $postType;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

    /**
     * @param mixed $results
     */
    public function addResults($results)
    {
        $this->results[] = $results;
    }

    /**
     * Method will prepare CURL request
     * @param string $method - method to call
     * @param string $url - URL to call
     * @param string $postQueryData - data to be send via POST in key=value URL format (encode it via example http_build_query function
     * @return mixed
     */
    public function addCall($method, $url, array $postQueryData = array(), $postType = 'QUERY')
    {
        $curl = new Curl();

        $urlDbPrefix = str_replace(
            array(
                '%db%'
            ),
            array(
                $this->getDb()
            ),
            self::API_URL_DBPRFIX
        );

        $post = $curl->encodePostQuery($postQueryData, $postType);
        $url = $url . ($post['url'] ? '&' . $post['url'] : '');

        $hmac = new Hmac($this->getKey());
        $urlHashed = $this->getHost() . $hmac->hashQuery($urlDbPrefix . $url);

        $this->setMethod($method);
        $this->setUrl($urlHashed);
        $this->setPostQueryData($postQueryData);
        $this->setPostType($postType);

        $curl->addCall(
            $this->getMethod(),
            $this->getUrl(),
            $this->getPostQueryData(),
            $this->getPostType()
        );

        self::addResults( $curl->process() );
    }

    /**
     * Method will return results of API calls
     * @return array
     */
    public function process()
    {
        return self::getResults();
    }
}