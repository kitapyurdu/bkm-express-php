<?php

require_once './setup.php';

Log::debug('HTTP REQUEST TO => '.__FILE__);

require_once './BexUtil.php';

function writeDb($orderId, $status, $message, $error = false, $detail = null)
{
    $table = BexUtil::readJsonFile(DATA_FILE_NAME);
    $orderData = $table[$orderId];
    if (!$orderData) {
        throw new Exception("Order not found by $orderId orderId");
    }
    $orderData['status'] = $status;
    $orderData['message'] = $message;
    $orderData['error'] = $error;
    $orderData['detail'] = $detail;
    $table[$orderId] = $orderData;

    Log::debug(__METHOD__, (array) $orderData);
    BexUtil::writeJsonFile(DATA_FILE_NAME, $table);
}

$orderId = null;

try {
    $nonceResult = $bex->approve(function ($data) {
        global $orderId;
        $orderId = $data['reply']['orderId'];

        $table = BexUtil::readJsonFile(DATA_FILE_NAME);

        $orderData = &$table[$orderId];
        // $orderData["orderId"] == $data["reply"]["orderId"] // ticket oluştururken orderId gönderimi yaparsanız nonce'ta sisteminizdeki orderId ile kontrol sağlayabilirsiniz.
        if ($orderData && $orderData['amount'] == $data['reply']['totalAmount']) {
            $orderData['status'] = 'Approved';
            $orderData['message'] = 'Ödeme onaylandı';
            Log::debug($data);
            $orderData['transactionToken'] = $data['id'];
            BexUtil::writeJsonFile(DATA_FILE_NAME, $table);

            return true;
        }
        $orderData['error'] = true;
        $orderData['status'] = 'Not_Approved';
        $orderData['message'] = 'Ödeme reddedildi !';
        BexUtil::writeJsonFile(DATA_FILE_NAME, $table);

        return false;
    });
    Log::debug(__FILE__.' - nonceResult::getPaymentPurchased => '. ((int) $nonceResult->getPaymentPurchased()));
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
} catch (\GuzzleHttp\Exception\ClientException $exception) {
    Log::debug(__FILE__.' - EXCEPTION => '. $exception->getRequest()->getBody()->getContents());
    Log::debug(__FILE__.' - EXCEPTION => '. $exception->getMessage());
    writeDb($orderId, $exception->getCode(), 'Ödeme yapılamadı !', true, $exception->getMessage());
} catch (Exception $exception) {
    Log::debug(__FILE__.' - EXCEPTION => '. $exception->getMessage());
    writeDb($orderId, $exception->getCode(), 'Ödeme yapılamadı !', true, $exception->getMessage());
}
