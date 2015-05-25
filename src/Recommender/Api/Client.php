<?php
/**
 * Class responsible of API comucation
 * @package Recommender/Api
 * @copyright (c) Modgen s.r.o. 2015
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
     * @var string - ADD purchase
     */
    const API_URL_ADDPURCHASE = '/%db%/purchases';

    /*
     * @var string - Hmac authentication check string 1
     */
    const API_HMAC_AUTH_CHECK_STRING_1 = '/modgen/items/9346/recomms/?count=5&targetUserId=fb2fbe12-9f69-45a1-9fc0-df0c1592e4c7&hmac_timestamp=1398463889';

    /*
     * @var string - Hmac authentication check string 1
     */
    const API_HMAC_AUTH_CHECK_STRING_2 = 'Hello world';

    /*
     * @var string - Hmac authentication result string 1
     */
    const API_HMAC_AUTH_CHECK_RESULT_1 = 'a12b51a127be379638cbc4f90348e163c835ac81';

    /*
     * @var string - Hmac authentication result string 2
     */
    const API_HMAC_AUTH_CHECK_RESULT_2 = '1291b164d8332792233dcc8ce94e1c9ea6113fb8';

    /*
     * @var string - Modgen API DB ID
     */
    private $host = 'https://rapi.modgen.net';

    /*
     * @var string - Modgen API DB ID
     */
    private $db = '';

    /*
     * @var string - unique API key
     */
    private $key = '';

    /*
     * @var boolean - enable/disable debug mode
     */
    private $debug = false;

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
     * Method will set API host
     * @param string $host - DB ID where data will be stored
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Method will set debug mode
     * @param string $debug - true/false
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        if ($this->debug) {
            echo "<pre>";
        }
    }

    /**
     * Method is responsible of adding products to Modgen Recommender API
     * @param array $data - list of arrays with data
     * @param string $keyElement - definition of main key fron data array
     */
    public function addProducts($data, $keyElement)
    {
        if (!isset($data[0]) || is_array($data[0])) {
            $data[] = $data;
        }

        foreach ($data as $key => $p) {
            self::addProductID($p[$keyElement]);
            self::addProductProperties($p);
            self::addProductValues($p[$keyElement], $p);
        }
    }

    /**
     * Method is responsible of adding purchases to Modgen Recommender API
     * @param array $data - list of arrays with data
     */
    public function addPurchases($data)
    {
        if (!is_array($data) && count($data) < 2) {
            $data[] = $data;
        }

        foreach ($data as $key => $p) {
        }
    }

    /**
     * Method is check if HMAC authentication workd
     * @param string $key - hash key
     * @return void
     */
    public function checkHmacAuthentication($key)
    {
        $hmac = new Hmac($key);

        $hash = $hmac->checkAuth(self::API_HMAC_AUTH_CHECK_STRING_1);
        echo "Hashed: " . $hash . ", Expected: " . self::API_HMAC_AUTH_CHECK_RESULT_1 . ", Same: " . ( $hash === self::API_HMAC_AUTH_CHECK_RESULT_1 ? 'true' : 'false') . "<br>";

        $hash2 = $hmac->checkAuth(self::API_HMAC_AUTH_CHECK_STRING_2);
        echo "Hashed: " . $hash2 . ", Expected: " . self::API_HMAC_AUTH_CHECK_RESULT_2 . ", Same: " . ( $hash2 === self::API_HMAC_AUTH_CHECK_RESULT_2 ? 'true' : 'false');

        if ($this->debug) {
            die();
        }
    }

    /**
     * Method will add product ID to DB
     * @param string $productID - Item ID
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
                $productID,
            ),
            self::API_URL_ADDITEM
        );

        $result = self::call('PUT', $url);

        if ($this->debug) {
            print_r($result);
        }
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

            $result = self::call('PUT', $url);

            if ($this->debug) {
                print_r($result);
            }
        }
    }

    /**
     * Method will add properties in to product ID
     * @param string $productID - Item ID
     * @param array $products - list of properties to add (connect to item id)
     * @return boolean
     */
    private function addProductValues($productID, array $products)
    {
        $query = http_build_query($products);
        $url = str_replace(
            array(
                '%db%',
                '%itemid%'
            ),
            array(
                $this->db,
                $productID,
            ),
            self::API_URL_ADDITEM_VALUES
        );

        $result = self::call('POST', $url, $query);

        if ($this->debug) {
            print_r($result);
        }
    }

    /**
     * Method will add properties in to product ID
     * @param string $productID - Item ID
     * @param array $products - list of properties to add (connect to item id)
     * @return boolean
     */
    private function addPurchase(array $purchases)
    {
        $query = http_build_query($purchases);
        $url = str_replace(
            array(
                '%db%'
            ),
            array(
                $this->db
            ),
            self::API_URL_ADDPURCHASE
        );

        $result = self::call('POST', $url, $query);

        if ($this->debug) {
            print_r($result);
        }
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
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postQueryData);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
        }
        curl_setopt($curl, CURLOPT_URL, $this->host . $urlHashed);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}
