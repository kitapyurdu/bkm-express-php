<?php

namespace Bex\easy;

use Bex\config\BexPayment;
use Bex\exceptions\BexException;
use Bex\exceptions\ConfigurationException;
use Bex\exceptions\MerchantServiceException;
use Bex\merchant\request\Builder;
use Bex\merchant\request\InstallmentRequest;
use Bex\merchant\request\NonceRequest;
use Bex\merchant\response\BinAndInstallments;
use Bex\merchant\response\InstallmentsResponse;
use Bex\merchant\response\nonce\MerchantNonceResponse;
use Bex\merchant\response\TicketRefresh;
use Bex\merchant\security\EncryptionUtil;
use Bex\merchant\service\MerchantService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;

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
     *
     * @return Bex
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
            'id' => $ticketRefresh->getId(),
            'path' => $ticketRefresh->getPath(),
            'token' => $ticketRefresh->getToken(),
        );
    }

    /**
     * {.
     *
     * }
     *
     * @param $ticketArray
     *
     * @return array
     *
     * @throws BexException
     */
    public function createTicket($ticketArray)
    {
        $this->login();
        $amount = $ticketArray['amount'];
        if (empty($amount)) {
            throw new Exception('Toplam tutar zorunludur !');
        }
        $nonceUrl = $ticketArray['nonceUrl'];
        if (empty($nonceUrl)) {
            throw new Exception('Nonce URL zorunludur !');
        }
        $builder = new Builder('payment');
        $builder->setAmount($ticketArray['amount']);
        $builder->setInstallmentUrl($ticketArray['installmentUrl']);
        $builder->setNonceUrl($ticketArray['nonceUrl']);
        $builder->setCampaignCode($ticketArray['campaignCode']);
        $builder->setOrderId($ticketArray['orderId']);
        $builder->setTckn($ticketArray['tckn']);
        $builder->setMsisdn($ticketArray['msisdn']);
        $builder->setAddress($ticketArray['address']);
        $builder->setAgreementUrl($ticketArray['agreementUrl']);

        $ticketResponse = $this->merchantService->oneTimeTicketWithBuilder(
            $this->merchantLoginResponse->getToken(),
            $builder
        );

        return array(
            'id' => $ticketResponse->getTicketShortId(),
            'path' => $ticketResponse->getTicketPath(),
            'token' => $ticketResponse->getTicketToken(),
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

    public function queryTicket($ticketPath)
    {
        $this->login();
        $client = new Client();
        try {
            $res = $client->request('POST', $this->merchantService->getMerchantResultUrl(
                $this->merchantLoginResponse->getPath(),
                $ticketPath
            ), $this->merchantService->getRequestOptions(
                $this->merchantLoginResponse->getConnectionToken()
            ));
            if (200 === $res->getStatusCode()) {
                return $res->getBody()->getContents();
            }
        } catch (HttpRequestException $exception) {
            throw new MerchantServiceException('Ticket result got connection problem.');
        } catch (ServerException $exception) {
            throw new MerchantServiceException($exception->getMessage());
        }
    }

    public function installments(callable $callback)
    {
        //DONEN RESPONSE UMUZ APPLICATION/JSON FORMATINDA OLMALIDIR.
        header('Content-type: application/json');
        // REQUEST DATASINI ALDIGIMIZ YER
        $data = json_decode(file_get_contents('php://input'), true);
        $installmentResponse = new InstallmentsResponse();
        if (empty($data)) {
            throw new BexException('Request body can not get !');
        }
        //GELEN REQUEST DATASI ILE OBJEMIZI OLUSTRUYORUZ.
        $installmentRequest = new InstallmentRequest($data['bin'], $data['totalAmount'], $data['ticketId'], $data['signature']);
        //REQUESTTE GELEN SIGNATURE DEGERINI EGER UZERINDE OYNANMIS ISE HATALI OLARAK DONUYORUZ.
        //KONTROLLERI ENCRYPTION UTIL SINIFINDA YAPILMAKTADIR.
        if (!EncryptionUtil::verifyBexSign(
            $installmentRequest->getTicketId(),
            $installmentRequest->getSignature()
        )
        ) {
            // SIGN ERROR
            exit(json_encode(array('data' => null, 'status' => 'fail', 'error' => 'Signature verification failed.')));
        }
        try {
            //REQUESTTEN GELEN BIN IN 0. ELEMENTINDE BIN AND BANK I DEGERE ATIYORUZ.
            $binAndBank = $installmentRequest->getBinNo()[0];
            //BANKA KODUNU EXPLODEDARR E ATIYORUZ.
            $explodedArr = explode('@', $binAndBank);
            // get installments
            $installments = $callback(array(
                'ticketId' => $installmentRequest->getTicketId(),
                'totalAmount' => $installmentRequest->getTotalAmount(),
                'bin' => $explodedArr[0],
                'bank' => $explodedArr[1],
            ));
            //DONECEGIMIZ GENEL RESPONSE TIPI
            $binAndInstallments = new BinAndInstallments();
            $installmentResponse->setInstallments($installments);
            $installmentResponse->setStatus('ok');
            $installmentResponse->setBin($explodedArr[0]);
            $returnArray = array();
            $returnArray[$installmentResponse->getBin()] = $installmentResponse->getInstallments();
            $binAndInstallments->setInstallments($returnArray);
            exit(json_encode(array('data' => $binAndInstallments, 'status' => 'ok', 'error' => '')));
        } catch (\Exception $exception) {
            exit(json_encode(array('data' => null, 'status' => 'fail', 'error' => $exception->getMessage())));
        }
    }

    public function approve(callable $callback)
    {
        $this->login();
        //NULL CHECK
        $data = $this->takeDataAndRespond();
        if (null != $data) { // Data is ok.
            //NONCE REQUEST I SETLIYORUZ.
            //NONCE REQUESTTEN BIZE ASAGIDAKI GIBI SETLENMIS DATALAR GELMEKTEDIR.
            $nonceRequest = new NonceRequest(
                $data['id'],
                $data['path'],
                $data['issuer'],
                $data['approver'],
                $data['token'],
                $data['signature'],
                $data['reply'],
                $data['hash'],
                $data['tcknMath'],
                $data['msisdnMatch']
            );
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
            if (EncryptionUtil::verifyBexSign($nonceRequest->getTicketId(), $nonceRequest->getSignature())) {
                //DONULMESI GEREKEN RESPONSE'UN KOD ORNEGI.
                $merchantNonceResponse->setResult($callback($data));
                $merchantNonceResponse->setNonce($nonceRequest->getToken());
                $merchantNonceResponse->setId($nonceRequest->getPath());
                //NONCE RESPONSU SETLEDIKDEN SONRA MERCHANT SERVICE DEN SENDNONCERESPONCE SERVISINI CAGIRIYORUZ.
                //PARAMETRELER SIRASIYLA
                //1-)SETLEDIGIMIZ RESPONSE SINIFI
                //2-)MERCHANTLOGINDEN DONEN PATH
                //3-)NONCE REQUESTTEN GELEN PATH
                //4-)MERCHANT LOGINDEN GELEN CONNECTION TOKEN
                //5-)NONCE REQUESTTEN GELEN TOKEN
                $this->merchantService->sendNonceResponse(
                    $merchantNonceResponse,
                    $this->merchantLoginResponse->getPath(),
                    $nonceRequest->getPath(),
                    $this->merchantLoginResponse->getConnectionToken(),
                    $nonceRequest->getToken()
                );
            } else {
                //BASARISIZ RESPONSE ORNEGI
                //BURADA RESULT FALSE OLARAK SETLENIR VE MESSAGE SETLENIR.
                $merchantNonceResponse->setResult(false);
                $merchantNonceResponse->setNonce($nonceRequest->getToken());
                $merchantNonceResponse->setId($nonceRequest->getPath());
                $merchantNonceResponse->setMessage('Signature verification failed');
                $this->merchantService->sendNonceResponse(
                    $merchantNonceResponse,
                    $this->merchantLoginResponse->getPath(),
                    $nonceRequest->getPath(),
                    $this->merchantLoginResponse->getConnectionToken(),
                    $nonceRequest->getToken()
                );
            }

            return $nonceRequest;
        }
    }

    /**
     * respondOK.
     */
    protected function takeDataAndRespond()
    {
        //ISTEKDEN GELEN REQUESTI ALIYORUZ.
        $data = json_decode(file_get_contents('php://input'), true);
        //NULL CHECK
        if (null != $data) {
            //DONULMESI GEREKEN RESPONSE ORNEGI JSON FORMATINDA OLMALIDIR.
            header('Content-type: application/json');
            // NONCE ILK RESPONSE
            //ILK RESPONSE UN FORMATINA DIKKAT EDILMELIDIR.
            ob_start();
            echo json_encode(array(
                'result' => 'ok',
                'data' => 'ok',
            ));
            $size = ob_get_length();
            // Disable compression (in case content length is compressed).
            header('Content-Encoding: none');
            header($_SERVER['SERVER_PROTOCOL'].' 202 Accepted');
            header('Status: 202 Accepted');
            header("Content-Length: {$size}");
            header('Connection: close');
            ignore_user_abort(true);
            set_time_limit(0);
            ob_end_flush();
            ob_flush();
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
