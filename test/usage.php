<?php
/**
 * Created by PhpStorm.
 * User: mirekratman
 * Date: 25/05/15
 * Time: 10:33
 */

ERROR_REPORTING(E_ALL);
require __DIR__ . "/../src/loader.php";

$db = 'shopexpo-test';
$key = 'DyioS5vct4fyqbjjr7Yno8dUFALYjAZe0JP3yR65aCNdtbjk92F9gxU1yDAVR7QS';
$classApiClient = new \Recommender\Api\Client($db, $key);

$products = array(
    0 => array(
        'id' => 'item-350',
        'name' => 'Jizdni kolo',
        'description' => 'Sahodlouhy popis produktu',
        'price' => '7500'
    ),
    1 => array(
        'id' => 'item-351',
        'name' => 'Zimni bunda',
        'description' => 'Nejaky jiny popis',
        'price' => '2000'
    )
);

$classApiClient->setDebug(true);
$classApiClient->checkHmacAuthentication('gahpiev6eighaig1aek4ujietheiXeengae3Ohqu9iecutheof5rooxeigheel8G');

//$classApiClient->setHost('http://rapi-dev.modgen.net');
$classApiClient->addProducts($products,'id');