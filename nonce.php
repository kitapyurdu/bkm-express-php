<?php
//NONCE KOD ORNEGI
// Nonce onaylama fonksiyonu.

require_once "../../src/main/Bex/Bex.php";
require_once __DIR__.'/Bex.php';
require_once  __DIR__.'/config.php';
//VERILMIS OLAN SDK YI ZIP FORMATINDAN CIKARTIP PROJEMIZE EKLIYORUZ.
//SDKNIN PROJEYE YUKLENMESI
//DIKKAT EDILMESI GEREKEN SDK NIN PATHININ DOGRU VERILMESI.

use Bex\config\BexPayment;
use Bex\exceptions\BexException;
use Bex\exceptions\ConfigurationException;
use Bex\merchant\response\nonce\MerchantNonceResponse;
use Bex\merchant\response\TicketRefresh;
use Bex\merchant\security\EncryptionUtil;
use Bex\merchant\service\MerchantService;

function getValue($array, $key)
{
    if (array_key_exists($key, $array)) {
        return $array[$key];
    }
    return null;
}

Log::debug('Handle Nonce Request');

$serverUrl = "http://$_SERVER[HTTP_HOST]";
$bex = Bex::configure(
    ENVIRONMENT,
    MERCHANT_ID,
    PRIVATE_KEY
);

$nonceResult = $bex->approve(function ($data) {
    if ($data["reply"]["totalAmount"] === "1000") { // Nonce kontrolü
        return true; // onayla
    }
    return false; // reddet
});
Log::debug('nonce.php', [
    'line' => __LINE__,
    'nonceResult' => (array) $nonceResult,
]);

if ($nonceResult->getPaymentPurchased()) { // payment is ok.
    // İşlem tamamlandı. Ödeme yapıldı.
    // Bu durumda kendi sisteminizdeki siparişi güncelleyebilirsiniz.
} else {
    // Ödeme yapılamadı.
}
