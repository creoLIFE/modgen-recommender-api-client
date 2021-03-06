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
//$transport = new Recommender\Api\Transport\Transport();
//$transport->setBatchSize(4);
$transport->setDebug(true);
$transport->setBatchFileStorePath(__DIR__.'/store/');

$classApiClient = new \Recommender\Api\Client('http://rapi-dev.modgen.net', $db, $key, $transport);
//$classApiClient = new \Recommender\Api\Client('http://rapi-dev.modgen.net', $db, $key, new Recommender\Api\Transport\Transport());

$product = array(
    'id' => 'item-352',
    'name' => 'Jizdni kolo',
    'description' => 'Sahodlouhy popis produktu',
    'price' => 1432866460,
    'available'=>12112
);

$products = array(
    0 => array(
        'id' => 'item-400',
        'name' => null,
        'description' => null,
        'price' => null,
        'available'=>false
    ),
    1 => array(
        'id' => 'item-401',
        'name' => 'Zimni bunda 1',
        'description' => 'Nejaky jiny popis 1',
        'price' => 2010,
        'available'=>null
    ),
    2 => array(
        'id' => 'item-402',
        'name' => 'Zimni bunda 1',
        'description' => 'Nejaky jiny popis 1',
        'price' => 2010,
        'available'=>12112
    ),
    3 => array(
        'id' => 'ACPLJETPUX140',
        'name' => 'JEKOD TPU silikonové pouzdro i9190 S4 mini, Black',
        'description' => 'JEKOD TPU silikonové ochranné pouzdro pro Samsung i9190 Galaxy S4 mini, barva: Black',
        'price' => "114,88",
        'available'=>false
    )
);

$purchase = array(
    'itemId' => 'false',
    'userId' => 'AAABSCgTw5rtOLpwHdE4WTgc',
    'timestamp' => '2014-08-29T20:22:28.789000'
);

$classApiClient->setDebug(true);
//$classApiClient->checkHmacAuthentication('gahpiev6eighaig1aek4ujietheiXeengae3Ohqu9iecutheof5rooxeigheel8G');


$classApiClient->deleteDb();
$classApiClient->addProducts($products,'id');
//$classApiClient->addProduct($product,'id');
//$classApiClient->addPurchase($purchase);

$classApiClient->process();


