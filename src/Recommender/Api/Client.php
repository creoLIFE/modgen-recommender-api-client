<?php
/**
 * Created by PhpStorm.
 * User: mirekratman
 * Date: 22/05/15
 * Time: 11:48
 */

/**
 * Class responsible of API comucation
 * @package Recommender/Api
 * @copyright Modgen s.r.o.
 * @author Mirek Ratman
 * @version 1.0
 * @since 2015-05-21
 * @license Modgen s.r.o.
 */

namespace Recommender\Api;

use Recommender\Api\Helpers\Hmac;
use Recommender\Api\Helpers\Property;

class Client
{
    /*
     * @var string - ADD Item URL
     */
    const API_URL_ADDITEM = '/%db%/items/%itemid%';

    /*
     * @var string - ADD Item property
     */
    const API_URL_ADDITEM_PROPERTIES = '/%db%/items/properties/%propertyname%?type=%type%';

    /*
     * @var string - ADD Item values
     */
    const API_URL_ADDITEM_VALUES = '/%db%/items/%itemid%';

    /*
     * @var string - Modgen API DB ID
     */
    protected $db = '';

    /*
     * @var string - unique API key
     */
    protected $key = '';

    /**
     * Class constructor
     * @param string $db - DB ID where data will be stored
     * @param string $key - unique API key
     */
    public function __construct($db, $key)
    {
        $this->db = $db;
        $this->key = $key;
    }

    /**
     * Method is responsible of adding products to Modgen Recommender API
     * @param array $data - list of arrays with data
     * @param string $keyElement - definition of main key fron data array
     */
    public function addProducts($data, $keyElement)
    {
        if (!is_array($data) && count($data) < 2) {
            $data[] = $data;
        }

        foreach ($data as $key => $p) {
            self::addProductID($p[$keyElement]);
            self::addProductProperties($p);
            self::addProductValues($p);
        }
    }

    /**
     * Method is responsible of adding purchases to Modgen Recommender API
     * @param array $data - list of arrays with data
     * @param string $keyElement - definition of main key fron data array
     */
    public function addPurchases($data, $keyElement)
    {
        if (!is_array($data) && count($data) < 2) {
            $data[] = $data;
        }

        foreach ($data as $key => $p) {
        }
    }

    /**
     * Method will add product ID to DB
     * @param string $productId - Item ID
     * @return boolean
     */
    private function addProductID($productID)
    {
        $url = str_replace(
            array(
                '%db%',
                '%itemid%'
            ),
            array(
                $this->db,
                $productId,
            ),
            self::API_URL_ADDITEM
        );

        self::call('PUT', $url);
    }

    /**
     * Method will add properties in to product ID
     * @param array $products - list of properties to add (connect to item id)
     * @return boolean
     */
    private function addProductProperties(array $products)
    {
        foreach ($products as $key => $val) {
            $url = str_replace(
                array(
                    '%db%',
                    '%propertyname%',
                    '%type%'
                ),
                array(
                    $this->db,
                    $key,
                    Property::getPropertyType($val)
                ),
                self::API_URL_ADDITEM_PROPERTIES
            );

            self::call('PUT', $url);
        }
    }

    /**
     * Method will add properties in to product ID
     * @param array $products - list of properties to add (connect to item id)
     * @return boolean
     */
    private function addProductValues(array $products)
    {
        $query = http_build_query($products)
        $url = str_replace(
            array(
                '%db%',
                '%itemid%'
            ),
            array(
                $this->db,
                $itemId,
            ),
            self::API_URL_ADDITEM_VALUES
        );

        self::call('POST', $url, $query);
    }

    /**
     * Method will add product ID to DB
     * @param string $method - method to call
     * @param string $url - URL to call
     * @param string $postQueryData - data to be send via POST in key=value URL format (encode it via example http_build_query function
     * @return boolean
     */
    private function call($method, $url, $postQueryData = false)
    {
        $curl = curl_init();

        $hmac = new Hmac($this->key);
        $urlHashed = $hmac->hashQuery($url);

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($postQueryData) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
        }
        curl_setopt($curl, CURLOPT_URL, $urlHashed);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}
