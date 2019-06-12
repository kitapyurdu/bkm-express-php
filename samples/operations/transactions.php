<?php

require_once __DIR__.'/setup.php';

Log::debug('HTTP REQUEST TO => '.__FILE__);

require_once __DIR__.'/Bex.php';
require_once __DIR__.'/BexUtil.php';

$request_body = json_decode(file_get_contents('php://input'));

$filename = './data.json';
$table = BexUtil::readJsonFile($filename);

$orderId = @$_GET['orderId'];
$ticket = @$_GET['ticket'];

if ($orderId && $ticket) {
    $ticketResponse = $bex->transactionDetail($orderId, $ticket);
} else {
    $ticketResponse = $bex->transactionList();
}


exit(json_encode([
    'status' => 'SUCCESS',
    'response' => $ticketResponse,
]));
