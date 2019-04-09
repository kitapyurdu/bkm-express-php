<?php

require_once './setup.php';
require_once './BexUtil.php';

$request_body = json_decode(file_get_contents('php://input'));

$filename = './data.json';
$table = BexUtil::readJsonFile($filename);
$orderData = $table[$request_body->orderId];
$orderData['orderId'] = $request_body->orderId;
exit(json_encode($orderData, true));
