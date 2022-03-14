<?php
require __DIR__ . '/../vendor/autoload.php';

$client = new \bingher\transmit\Client('127.0.0.1', 9000);

$res = $client->sayMsg(['msg' => 'hello huangbh']);
var_dump($res);
