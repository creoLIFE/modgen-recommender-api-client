<?php
/**
 * Created by PhpStorm.
 * User: mirekratman
 * Date: 25/05/15
 * Time: 10:33
 */

ERROR_REPORTING(E_ALL);
require __DIR__ . "/../src/loader.php";

$db = '';
$key = '';
$classApiClient = new \Recommender\Api\Client('http://rapi-dev.modgen.net', $db, $key, new Recommender\Api\Transport\Batch());
//$classApiClient = new \Recommender\Api\Client('http://rapi-dev.modgen.net', $db, $key, new Recommender\Api\Transport\Transport());

$product = array(
    'id' => 'item-352',
    'name' => 'Jizdni kolo',
    'description' => 'Sahodlouhy popis produktu',
    'price' => '7500'
);

$products = array(
    0 => array(
        'id' => 'item-400',
        'name' => 'Jizdni kolo 1',
        'description' => 'Sahodlouhy popis produktu 1',
        'price' => '7000'
    ),
    1 => array(
        'id' => 'item-401',
        'name' => 'Zimni bunda 1',
        'description' => 'Nejaky jiny popis 1',
        'price' => '2010'
    )
);

$classApiClient->setDebug(true);
//$classApiClient->checkHmacAuthentication('gahpiev6eighaig1aek4ujietheiXeengae3Ohqu9iecutheof5rooxeigheel8G');

//$classApiClient->addProduct($product,'id');
$classApiClient->addProducts($products,'id');

