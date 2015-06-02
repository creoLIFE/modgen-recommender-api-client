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
use Recommender\Api\Helpers\Roles;
use Recommender\Api\Transport\Transport;

class Client
{
    /*
     * @var string - ADD Item URL
     */
    const API_URL_ADDITEM = '/items/%itemid%';

    /*
     * @var string - ADD Item property
     */
    const API_URL_ADDITEM_PROPERTIES = '/items/properties/%propertyname%';

    /*
     * @var string - ADD Item property
     */
    const API_URL_ADDITEM_PROPERTIES_ROLES = '/items/properties/roles/%rolename%';

    /*
     * @var string - ADD Item values
     */
    const API_URL_ADDITEM_VALUES = '/items/%itemid%';

    /*
     * @var string - ADD purchase
     */
    const API_URL_ADDPURCHASE = '/purchases/';

    /*
     * @var string - Delete DB
     */
    const API_URL_DELETEDB = '/';

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
     * @var mixed - Instance of Transport class
     */
    private $transport;

    /*
     * @var boolean - enable/disable debug mode
     */
    private $debug = false;

    /*
     * @var boolean - tell if properties was already added in last request
     */
    private $propertiesAdded = false;

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
     * @return mixed
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param mixed $transport
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return boolean
     */
    public function isPropertiesAdded()
    {
        return $this->propertiesAdded;
    }

    /**
     * @param boolean $propertiesAdded
     */
    public function setPropertiesAdded($propertiesAdded)
    {
        $this->propertiesAdded = $propertiesAdded;
    }


    /**
     * Class constructor
     * @param string $host - Modgen API host
     * @param string $db - DB ID where data will be stored
     * @param string $key - unique API key
     * @param Transport $transport
     */
    public function __construct($host, $db, $key, Transport $transport)
    {
        $this->setHost($host);
        $this->setDb($db);
        $this->setKey($key);

        $transport->setDb($this->getDb());
        $transport->setKey($this->getKey());
        $transport->setHost($this->getHost());

        $this->setTransport($transport);
    }

    /**
     * Method is responsible of adding products to Modgen Recommender API
     * @param array $dataList - list of arrays with data
     * @param string $keyElement - definition of main key fron data array
     */
    public function addProducts($dataList, $keyElement)
    {
        if (!is_array($dataList) && count($dataList) < 2) {
            $dataList[] = $dataList;
        }

        foreach ($dataList as $key => $p) {
            self::addProduct($p, $keyElement);
            $this->setPropertiesAdded(true);
        }
    }

    /**
     * Method is responsible of adding products to Modgen Recommender API
     * @param array $data - array with data
     * @param string $keyElement - definition of main key fron data array
     * @param array $rolesDefinition - definition of roles for properties
     */
    public function addProduct($data, $keyElement)
    {
        self::addProductID($data[$keyElement]);
        if( !$this->isPropertiesAdded() ) {
            self::addProductProperties($keyElement, $data);
            self::addProductPropertiesRoles($keyElement, $data);
            $this->setPropertiesAdded(true);
        }
        self::addProductValues($keyElement, $data[$keyElement], $data);
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
            self::addPurchase($p);
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
        echo "String: " . self::API_HMAC_AUTH_CHECK_STRING_1 . ", Hashed: " . $hash . ", Expected: " . self::API_HMAC_AUTH_CHECK_RESULT_1 . ", Same: " . ($hash === self::API_HMAC_AUTH_CHECK_RESULT_1 ? 'true' : 'false') . "<br>";

        $hash2 = $hmac->checkAuth(self::API_HMAC_AUTH_CHECK_STRING_2);
        echo "String: " . self::API_HMAC_AUTH_CHECK_STRING_2 . ", Hashed: " . $hash2 . ", Expected: " . self::API_HMAC_AUTH_CHECK_RESULT_2 . ", Same: " . ($hash2 === self::API_HMAC_AUTH_CHECK_RESULT_2 ? 'true' : 'false');

        if ($this->debug) {
            die();
        }
    }

    /**
     * Method is responsible of adding products to Modgen Recommender API
     * @param array $data - array with data
     * @param string $keyElement - definition of main key fron data array
     * @param boolean $propertiesAlreadySet - definition of main key fron data array
     */
    public function deleteDb()
    {
        $transport = $this->getTransport();
        $transport->addCall('DELETE', self::API_URL_DELETEDB);
    }

    /**
     * Method will add product ID to DB
     * @param string $productID - Item ID
     * @return boolean
     */
    private function addProductID($productID)
    {
        $transport = $this->getTransport();
        $url = str_replace(
            array(
                '%itemid%'
            ),
            array(
                $productID,
            ),
            self::API_URL_ADDITEM
        );
        $transport->addCall('PUT', $url);
    }

    /**
     * Method will add properties in to product ID
     * @param string $keyElement - definition of main key fron data array
     * @param array $products - list of properties to add (connect to item id)
     * @return boolean
     */
    private function addProductProperties($keyElement, array $product)
    {
        if (isset($product[$keyElement])) {
            unset($product[$keyElement]);
        }

        $transport = $this->getTransport();
        foreach ($product as $key => $val) {
            $url = str_replace(
                array(
                    '%propertyname%'
                ),
                array(
                    $key
                ),
                self::API_URL_ADDITEM_PROPERTIES
            );
            $transport->addCall('PUT', $url, array('type'=>Property::getPropertyType($val, $key)), 'GET');
        }
    }

    /**
     * Method will add properties in to product ID
     * @param string $keyElement - definition of main key fron data array
     * @param array $products - list of properties to add (connect to item id)
     * @return boolean
     */
    private function addProductPropertiesRoles($keyElement, array $product)
    {
        if (isset($product[$keyElement])) {
            unset($product[$keyElement]);
        }

        $transport = $this->getTransport();
        foreach ($product as $key => $val) {
            $role = Roles::getPropertyType($key);
            $url = str_replace(
                array(
                    '%rolename%'
                ),
                array(
                    $role
                ),
                self::API_URL_ADDITEM_PROPERTIES_ROLES
            );

            if( $role ){
                $transport->addCall('PUT', $url, array('propertyName'=>$key), 'GET');
            }
        }
    }

    /**
     * Method will add properties in to product ID
     * @param string $keyElement - definition of main key fron data array
     * @param string $productID - Item ID
     * @param array $products - list of properties to add (connect to item id)
     * @return boolean
     */
    private function addProductValues($keyElement, $productID, array $product)
    {
        if (isset($product[$keyElement])) {
            unset($product[$keyElement]);
        }

        $transport = $this->getTransport();
        $url = str_replace(
            array(
                '%itemid%'
            ),
            array(
                $productID,
            ),
            self::API_URL_ADDITEM_VALUES
        );

        $out = array();
        foreach($product as $key=>$val){
            if( $key === 'price' && Property::getPropertyType($val,$key) === 'double' ){
                $val = str_replace(',','.',$val);
                $val = (double)$val;
            }

            if( $key === 'available' ){
                $val = $val === true ? 1 : 0;
            }
            $out[$key] = $val;
        }

        $transport->addCall('POST', $url, $out);
    }

    /**
     * Method will add properties in to product ID
     * @param array $data - list of properties to add (connect to item id)
     * @return boolean
     */
    public function addPurchase(array $data)
    {
        $transport = $this->getTransport();
        $out = array();
        foreach($data as $key=>$val){
            if( $key === 'timestamp' && Property::getPropertyType($val) === 'timestamp' ){
                $val = strtotime($val);
            }
            $out[$key] = $val;
        }

        //Create item when not exist
        $out['cascadeCreate'] = true;

        $transport->addCall('POST', self::API_URL_ADDPURCHASE, $out);
    }

    /**
     * Method will process requests
     * @return mxed
     */
    public function process()
    {
        $transport = $this->getTransport();
        $result = $transport->process();

        if ($this->isDebug()) {
            print_r($result);
            print_r('<br>');
        }

        return $result;
    }
}