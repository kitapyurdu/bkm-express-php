<?php
require_once "./setup.php";
require_once "./Bex.php";
require_once "./BexUtil.php";

$request_body = json_decode(file_get_contents('php://input'));

$filename = "./data.json";
$table = BexUtil::readJsonFile($filename);
$orderId = $request_body->orderId;

$table[$orderId] = array(
    "amount" => $request_body->amount,
    "error" => false,
    "status" => "STARTED",
    "message" => "Ödeme aşamasına geçildi."
);
BexUtil::writeJsonFile($filename, $table);

$type = $request_body->type;

$data = $table[$request_body->orderId];

$nonceUrl = "$serverUrl/operations/nonce.php";

/**
 * Ticket Input
 * {
 *  "amount": "1000,56",  // zorunlu (Ödeme Tutarı)
 *  "installmentUrl": "https://isyeri.com/installment",  // opsiyonel ("SIZIN SERVER'INIZDA TAKSITLERI SORGULAMAK ICIN BIR URL")
 *  "nonceUrl": "https://isyeri.com/nonce",  // zorunlu ("SIPARISIN UYGUNLUGUNUN KONTROL EDILMESI ICIN URL")
 *  "campaignCode": "BKM1234",   // opsiyonel (Kampanya Kodu)
 *  "orderId": "123456", // opsiyonel. ( Sipariş Numarası - Her Ticket isteği için farklı bir değer içermeli)
 *  "tckn": {  // opsiyonel (number => "TC Kimlik Numarası", check => "BKM Express içerisinde kontrol edilsin mi")
 *    "no": "10000000146",
 *    "check": true
 *  },
 *  "msisdn": { // opsiyonel ('no' => "cep telefonu numarası", 'check' => "BKM Express içerisinde kontrol edilsin mi")
 *      "no": "5051234567",
 *      "check": true
 *  },
 *  "address": true,  // opsiyonel (adres entegrasyonları kullananlar için)
 *  "agreementUrl: "https://isyeri.com/agreement" // opsiyonel ("Mesafeli satış ve ön bilgilendirme formu için sizin sitenize yönlenecek url") adres entegrasyonları için zorunlu
 * }
 */
$ticketData = [
    "amount" => $data['amount'],
    "nonceUrl" => $nonceUrl,
    "orderId" => $request_body->orderId
];

if ($type == "payment_with_installment") {
    $ticketData["installmentUrl"] = "$serverUrl/operations/installment.php";
}

if ($type == "payment_with_address") {
    $ticketData["address"] = true;
    $agreementUrl = "$serverUrl/operations/agreement.php";
    $ticketData["agreementUrl"] = $agreementUrl;
}

$ticketResponse = $bex->createTicket($ticketData);

exit(json_encode([
    "config" => array(
        "baseJs" => $bex->getBaseJs(),
        "baseUrl" => $bex->getBaseUrl()
    ),
    "response" => $ticketResponse
]));

?>