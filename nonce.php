<?php
//NONCE KOD ORNEGI
// Nonce onaylama fonksiyonu.

require_once "../../src/main/Bex/Bex.php";
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


class Bex
{
    private $config;
    private $isLoggedIn = false;
    private $merchantService;
    private $merchantLoginResponse;

    private function __construct($environment, $merchantId, $merchantPrivateKey)
    {
        $this->config = Bex::init($environment, $merchantId, $merchantPrivateKey);
    }

    private static function init($environment, $merchantId, $merchantPrivateKey)
    {
        if (!isset($environment)) {
            throw new ConfigurationException("BKM Express Ayar dosyasında environment değeri bulunamadı ! Ipucu: 'environment' değeri 'DEV', 'LOCAL', 'SANDBOX', 'PRODUCTION' ortamlarından birini belirtmelidir !");
        }
        if (!isset($merchantId)) {
            throw new ConfigurationException("BKM Express Ayar dosyasında id değeri bulunamadı ! Ipucu: 'id' değeri BKM Expressden size tahsis edilen tekil belirteçtir.!");
        }
        if (!isset($merchantPrivateKey)) {
            throw new ConfigurationException("BKM Express Ayar dosyasında privateKey değeri bulunamadı ! Ipucu:  'DEV', 'LOCAL', 'SANDBOX', 'PRODUCTION' değerlerinden biri olmalıdır !");
        }
        return BexPayment::startBexPayment($environment, $merchantId, $merchantPrivateKey);
    }

    /**
     * @param $environment
     * @param $merchantId
     * @param $merchantPrivateKey
     */
    public static function configure($environment, $merchantId, $merchantPrivateKey)
    {
        return new Bex($environment, $merchantId, $merchantPrivateKey);
    }

    public function refreshTicket($ticketArray)
    {
        $ticketResult = $this->createTicket($ticketArray);
        $ticketRefresh = new TicketRefresh(
            $ticketResult['id'],
            $ticketResult['path'],
            $ticketResult['token']
        );
        return array(
            "id" => $ticketRefresh->getId(),
            "path" => $ticketRefresh->getPath(),
            "token" => $ticketRefresh->getToken()
        );
    }

    private function login()
    {
        if (!$this->isLoggedIn) {
            $this->merchantService = new MerchantService($this->config);
            $this->merchantLoginResponse = $this->merchantService->login();
            $this->isLoggedIn = $this->merchantLoginResponse->getResult();
        }
        if (!$this->isLoggedIn) {
            $message = $this->merchantLoginResponse->getMessage();
            throw new BexException($message);
        }
    }

    public function approve(callable $callback)
    {
        $this->login();
        //NULL CHECK
        $data = $this->takeDataAndRespond();
        if ($data != null) { // Data is ok.
            //ILK RESPONSE U DONDUKDEN SONRA MICRO SERVIS  2. RESPONSU DONECEGIZ.
            //NONCE RESPONSE ORNEGI
            $merchantNonceResponse = new MerchantNonceResponse();
            //MERCHANT SERVICE I AYAGA KALDIRIYORUZ
            //YUKARIDA BEX:STARTPAYMENT TAN GELEN $config DEGISKENI
            // $merchantService = new MerchantService($this$config);

            //SIGNATURE KONTROLUNU YAPIYORUZ.
            //EGER SIGNATURE DOGRU ISE ISTENILEN RESPONSU AYARLIYORUZ.
            //BASARILI RESPONSE ORNEGI
            //SIGNATURE KONTROLUNDE TICKET ID OLARAK NONCE REQUESTTEN GELEN TICKET ID ILE KONTROL ETMEK GEREKMEKTEDIR
            //KONTROL EDILECEK OLAN SIGNATURE DA NONCE DAN GELMEKTEDIR.
            if (EncryptionUtil::verifyBexSign($data["id"], $data["signature"])) {
                //DONULMESI GEREKEN RESPONSE'UN KOD ORNEGI.
                $merchantNonceResponse->setResult($callback($data));
                $merchantNonceResponse->setNonce($data["token"]);
                $merchantNonceResponse->setId($data["path"]);
                //NONCE RESPONSU SETLEDIKDEN SONRA MERCHANT SERVICE DEN SENDNONCERESPONCE SERVISINI CAGIRIYORUZ.
                //PARAMETRELER SIRASIYLA
                //1-)SETLEDIGIMIZ RESPONSE SINIFI
                //2-)MERCHANTLOGINDEN DONEN PATH
                //3-)NONCE REQUESTTEN GELEN PATH
                //4-)MERCHANT LOGINDEN GELEN CONNECTION TOKEN
                //5-)NONCE REQUESTTEN GELEN TOKEN
                return $this->merchantService->sendNonceResponse(
                    $merchantNonceResponse,
                    $this->merchantLoginResponse->getPath(),
                    $data["path"],
                    $this->merchantLoginResponse->getConnectionToken(),
                    $data["token"]
                );
            } else {
                //BASARISIZ RESPONSE ORNEGI
                //BURADA RESULT FALSE OLARAK SETLENIR VE MESSAGE SETLENIR.
                $merchantNonceResponse->setResult(false);
                $merchantNonceResponse->setNonce($data["token"]);
                $merchantNonceResponse->setId($data["path"]);
                $merchantNonceResponse->setMessage("Signature verification failed");
                return $this->merchantService->sendNonceResponse(
                    $merchantNonceResponse,
                    $this->merchantLoginResponse->getPath(),
                    $data["path"],
                    $this->merchantLoginResponse->getConnectionToken(),
                    $data["token"]
                );
            }
        }
    }

    /**
     * respondOK.
     */
    protected function takeDataAndRespond()
    {
        //ISTEKDEN GELEN REQUESTI ALIYORUZ.
        $data = json_decode(file_get_contents('php://input'), TRUE);
        //NULL CHECK
        if ($data != null) {
            //DONULMESI GEREKEN RESPONSE ORNEGI JSON FORMATINDA OLMALIDIR.
            header('Content-type: application/json');
            // NONCE ILK RESPONSE
            //ILK RESPONSE UN FORMATINA DIKKAT EDILMELIDIR.
            ob_start();
            echo json_encode(array(
                "result" => "ok",
                "data" => "ok"
            ));
            $size = ob_get_length();
            // Disable compression (in case content length is compressed).
            header("Content-Encoding: none");
            header($_SERVER["SERVER_PROTOCOL"] . " 202 Accepted");
            header("Status: 202 Accepted");
            header("Content-Length: {$size}");
            header("Connection: close");
            ignore_user_abort(true);
            set_time_limit(0);
            ob_end_flush();
            flush();
            sleep(5);
            // check if fastcgi_finish_request is callable
            if (is_callable('fastcgi_finish_request')) {
                /*
                 * This works in Nginx but the next approach not
                 */
                session_write_close();
                fastcgi_finish_request();
            }
            return $data;
        }
        return null;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getBaseJs()
    {
        return $this->config->getBexApiConfiguration()->getBaseJs();
    }

    public function getBaseUrl()
    {
        return $this->config->getBexApiConfiguration()->getBaseUrl();
    }
}

const ENVIRONMENT = \Bex\enums\Environment::LOCAL; // Environments: DEV, LOCAL, SANDBOX, PRODUCTION
const MERCHANT_ID = "219be6b7-b3ca-4bd1-9886-a16d40b0bfe2";
const PRIVATE_KEY = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAOLA7InQdCbT8n5Rx8zk8uSCFQ5q4Tyxl0Kr02DoykWxLMBUl1p0YU9hoiocv6Hako5rZssHG0Eb4prh2nmZNpyfhOoOw48Pzg3eB7hKjpXLEPKdK8oemonBcvJ+E9/at4KLg4epyGum1cGdiaYkVF8frG+z53b0ngEq7/CzU8htAgMBAAECgYBNn6OZzf1lKVsy+QX/00R/CzTwGZB/eYABd9bFrwtHbk6WjJ6/fWWuigq8hdjoLG3NSWEIEae30zbwtG5ZACUcNa00Ar9mjsQncZXvLXp9hNb6/TR/mKQvZTjXgoRgn/ltS48GSpqWKbmKVl5JQWgNTb1zHGs2igBb161/ag16tQJBAPzVo2YAVcqXCvuNEWhkqsW1+74GSCrX5QcQwv8qwpt7raumojoFCdeW+xt1Je/bsw01pywkvI3cIO0pdHKwDDcCQQDll7GOPUT/q3Gvmw+kCTnvEH/yYSR2XsPLfEvewxp7SbFI1orLO61A+r5uLDGcfPoxQ7AORzf/OpSfNTD7IGZ7AkAUs5Fbaq+blN5rVlOUjpmE8q+YEX+bMm4oM/EjX2brwCaqJUynH358znnk96SRjRWOAVScwq1FmD6B7KECOvPlAkEA4GaWlXbPFLFGKaP98o9N/5p547YMxGE1L5LqOO0q2euaCp4fBCrs2MD7FYW+a7w/cZ0924bCdYSVNNLxb9IoNwJAJ6PVEsZWT5uGTxqlbTBDFSjHF79OLFWllHsa+2uwf/f6OwNAAMagVbWSdAIlZtaiifDhhXkC4h3ozI1f3xolJg==";

$serverUrl = "http://$_SERVER[HTTP_HOST]";
$bex = Bex::configure(
    ENVIRONMENT,
    MERCHANT_ID,
    PRIVATE_KEY
);

$nonceResult = $bex->approve(function ($data) {
    if ($data["reply"]["totalAmount"] === "1000,5") { // Nonce kontrolü
        return true; // onayla
    }
    return false; // reddet
});

if ($nonceResult->getPaymentPurchased()) { // payment is ok.
    // İşlem tamamlandı. Ödeme yapıldı.
    // Bu durumda kendi sisteminizdeki siparişi güncelleyebilirsiniz.
} else {
    // Ödeme yapılamadı.
}
