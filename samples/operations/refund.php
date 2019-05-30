<?php

require_once __DIR__.'/setup.php';

Log::debug('HTTP REQUEST TO => '.__FILE__);

require_once __DIR__.'/Bex.php';
require_once __DIR__.'/BexUtil.php';

$request_body = json_decode(file_get_contents('php://input'));

$filename = './data.json';
$table = BexUtil::readJsonFile($filename);
$orderId = $request_body->orderId;

$orderData = $table['31bd7cc0a6b76a436fd1720c'];
//$orderData = $table[$orderId];
$ticketResponse = $bex->refund($orderData);

exit(json_encode([
    'response' => $ticketResponse,
]));
