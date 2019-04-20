<?php

require_once './setup.php';

Log::debug('HTTP REQUEST TO => '.__FILE__);

require_once './BexUtil.php';

$filename = './data.json';
$table = BexUtil::readJsonFile($filename);

/**
 * {
 * "id": "9c50d4fe-719c-4e23-924c-baaa9cc85531",
 * "path": "bUAvbS8yMTliZTZiNy1iM2NhLTRiZDEtOTg4Ni1hMTZkNDBiMGJmZTIvdC85YzUwZDRmZS03MTljLTRlMjMtOTI0Yy1iYWFhOWNjODU1MzFAZ2UucGJ6Lm94ei5vcmsyLmZyZWlyZS5uY3YuenJlcHVuYWcuY25senJhZy5Dbmx6cmFnR3ZweHJn",
 * "issuer": "219be6b7-b3ca-4bd1-9886-a16d40b0bfe2",
 * "approver": "3573c761-8552-4b46-8154-51950345da17",
 * "token": "eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJiZXgiLCJzdWIiOiI5YzUwZDRmZS03MTljLTRlMjMtOTI0Yy1iYWFhOWNjODU1MzEiLCJpc3N1ZXIiOiIyMTliZTZiNy1iM2NhLTRiZDEtOTg4Ni1hMTZkNDBiMGJmZTIiLCJhcHByb3ZlciI6IjM1NzNjNzYxLTg1NTItNGI0Ni04MTU0LTUxOTUwMzQ1ZGExNyIsIm5vbmNlIjoiMzdhODIzMmEtMjRhMC00ZTJhLWFmZGEtM2NmMzk4Njk1MWMxIiwic2lkIjoiOWM1MGQ0ZmUtNzE5Yy00ZTIzLTkyNGMtYmFhYTljYzg1NTMxIiwidGlkIjoiL20vMjE5YmU2YjctYjNjYS00YmQxLTk4ODYtYTE2ZDQwYjBiZmUyL3QvOWM1MGQ0ZmUtNzE5Yy00ZTIzLTkyNGMtYmFhYTljYzg1NTMxIiwiY2xzIjoidHIuY29tLmJrbS5iZXgyLnNlcnZlci5hcGkubWVyY2hhbnQucGF5bWVudC5QYXltZW50VGlja2V0IiwiZXhwIjoxNTA1MTM4NDk3fQ.GBYvInCtt9KF3_z11H_7zSzrmrMv1ddD5pJRJ5gVktI",
 * "signature": "Qniv0q4cusVJh3EQMq+eJd0kfHn/3LuieoCEiwHPTTdfW4ZSI8TJXrkoXESjKymM83mXdqsB97F11LVxCHQaPe/eyl7oxACEGBL3FKvBLE35+OCUNAUjQGHdptx7PjfRBO56cby0lG+/KUgKHoyPqjgR1e5jKZOMNogOT9+vUNaIM063zBV6/J9XSalsLZP7wTjerQWHZdnxsq+WYEP7spr7rf9At12ohe8T8gnYFAVXHru3d3dJcFJRPpWulpE02HJUka4LwX5gJBGb5iDbGn+HUW7vv4wTDz7gPjWsDpOJN3ftXH1bo5oSho57I/JZ/5cVr6Ut9zNIANHu4EFI8w==",
 * "reply": {
 * "ticketId": "9c50d4fe-719c-4e23-924c-baaa9cc85531",
 * "orderId": "7282e6e1e5c94c45aba22f83851b36dd",
 * "totalAmount": "1000",
 * "totalAmountWithInstallmentCharge": "1000",
 * "numberOfInstallments": 1,
 * "hash": "16UEX3PJWWGJ+6IFTSVSO60EN38ZATL4FEW3YMLX2OK=",
 * "deliveryAddress": {
 * "addressType": "B",
 * "name": "asdasd",
 * "surname": "asdasda",
 * "companyName": "",
 * "address": "asdasdas",
 * "telephone": "1231231231",
 * "taxNumber": "",
 * "taxOffice": "",
 * "county": "Çukurova",
 * "city": "Adana",
 * "tckn": "12312312312",
 * "email": "zeynep@bkm.com"
 * },
 * "billingAddress": {
 * "addressType": "B",
 * "name": "asdasd",
 * "surname": "asdasda",
 * "companyName": "",
 * "address": "asdasdas",
 * "telephone": "1231231231",
 * "taxNumber": "",
 * "taxOffice": "",
 * "county": "Çukurova",
 * "city": "Adana",
 * "tckn": "12312312312",
 * "email": "zeynep@bkm.com"
 * }
 * }
 * }.
 *
 * @param $data
 */
$orderId = null;

function writeDb($orderId, $status, $message, $error = false, $detail = null)
{
    global $table;
    global $filename;
    $orderData = $table[$orderId];
    if (!$orderData) {
        throw new Exception("Order not found by $orderId orderId");
    }
    $orderData['status'] = $status;
    $orderData['message'] = $message;
    $orderData['error'] = $error;
    $orderData['detail'] = $detail;
    $table[$orderId] = $orderData;
    BexUtil::writeJsonFile($filename, $table);
}

try {
    $nonceResult = $bex->approve(function ($data) {
        global $orderId;
        $orderId = $data['reply']['orderId'];
        global $table;
        global $filename;
        $orderData = $table[$orderId];
        // $orderData["orderId"] == $data["reply"]["orderId"] // ticket oluştururken orderId gönderimi yaparsanız nonce'ta sisteminizdeki orderId ile kontrol sağlayabilirsiniz.
        if ($orderData && $orderData['amount'] == $data['reply']['totalAmount']) {
            $orderData['status'] = 'Approved';
            $orderData['message'] = 'Ödeme onaylandı';
            BexUtil::writeJsonFile($filename, $table);

            return true;
        }
        $orderData['error'] = true;
        $orderData['status'] = 'Not_Approved';
        $orderData['message'] = 'Ödeme reddedildi !';
        BexUtil::writeJsonFile($filename, $table);

        return false;
    });
    Log::debug(__FILE__.' - nonceResult::getPaymentPurchased => '. $nonceResult->getPaymentPurchased());
    Log::debug(__FILE__.' - nonceResult => '. serialize($nonceResult));
    if ($nonceResult->getPaymentPurchased()) { // payment is ok.
        // log($nonceResult->getCode());
        // log($nonceResult->getCall());
        // log($nonceResult->getDescription());
        // log($nonceResult->getMessage());
        // log($nonceResult->getResult());
        // error_log($nonceResult->getParameters());
        // log($nonceResult->getBkmTokenId());
        // log($nonceResult->getTotalAmount());
        // log($nonceResult->getInstallmentCount());
        // log($nonceResult->getCardFirst6());
        // log($nonceResult->getCardLast4());
        // log($nonceResult->getPaymentPurchased());
        // log($nonceResult->getStatus());
        // log($nonceResult->getCardHash());
        // log($nonceResult->getPosResult()->getOrderId());
        // log($nonceResult->getPosResult()->getAuthCode());
        // log($nonceResult->getPosResult()->getPosResponse());
        // log($nonceResult->getPosResult()->getPosResultCode());
        // log($nonceResult->getPosResult()->getReferenceNumber());
        // log($nonceResult->getPosResult()->getPosTransactionId());
        // log($nonceResult->getPosResult()->getPosBank());
        // log($nonceResult->getError());
        writeDb($orderId, 'SUCCESS', 'Ödeme tamamlandı');
    } else {
        writeDb($orderId, 'FAILED', 'Ödeme yapılamadı !', true);
    }
} catch (Exception $exception) {
    Log::debug(__FILE__.' - EXCEPTION => '. $exception->getMessage());
    writeDb($orderId, $exception->getCode(), 'Ödeme yapılamadı !', true, $exception->getMessage());
}
