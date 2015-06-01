<?php
/**
 * Created by PhpStorm.
 * User: mirekratman
 * Date: 25/05/15
 * Time: 10:33
 */

ERROR_REPORTING(E_ALL);
require __DIR__ . "/../src/loader.php";

date_default_timezone_set('UTC');

$db = 'shopexpo-test';
$key = 'DyioS5vct4fyqbjjr7Yno8dUFALYjAZe0JP3yR65aCNdtbjk92F9gxU1yDAVR7QS';
$transport = new Recommender\Api\Transport\Batch();
//$transport->setBatchSize(4);
$transport->setDebug(true);
$classApiClient = new \Recommender\Api\Client('http://rapi-dev.modgen.net', $db, $key, $transport);
//$classApiClient = new \Recommender\Api\Client('http://rapi-dev.modgen.net', $db, $key, new Recommender\Api\Transport\Transport());

$product = array(
    'id' => 'item-352',
    'name' => 'Jizdni kolo',
    'description' => 'Sahodlouhy popis produktu',
    'price' => 1432866460
);

$products = array(
    0 => array(
        'id' => 'item-400',
        'name' => 'Jizdni kolo 1',
        'description' => 'Sahodlouhy popis produktu 1',
        'price' => 2011
    ),
    1 => array(
        'id' => 'item-401',
        'name' => 'Zimni bunda 1',
        'description' => 'Nejaky jiny popis 1',
        'price' => 2010
    ),
    2 => array(
        'id' => 'item-402',
        'name' => 'Zimni bunda 1',
        'description' => 'Nejaky jiny popis 1',
        'price' => 2010
    )
);

$classApiClient->setDebug(true);
//$classApiClient->checkHmacAuthentication('gahpiev6eighaig1aek4ujietheiXeengae3Ohqu9iecutheof5rooxeigheel8G');

//$classApiClient->addProduct($product,'id');
$classApiClient->deleteDb();
$classApiClient->addProducts($products,'id');
$classApiClient->process();


