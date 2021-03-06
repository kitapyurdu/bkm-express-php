<?php

use Bex\config\BexPayment;
use Bex\exceptions\BexException;
use Bex\exceptions\ConfigurationException;
use Bex\merchant\request\Builder;
use Bex\merchant\request\InstallmentRequest;
use Bex\merchant\request\NonceRequest;
use Bex\merchant\response\BinAndInstallments;
use Bex\merchant\response\InstallmentsResponse;
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
    /** @var MerchantService */
    private $merchantService;

    /** @var \Bex\merchant\response\MerchantLoginResponse */
    private $merchantLoginResponse;

    private function __construct($environment, $merchantId, $merchantPrivateKey)
    {
        $this->config = Bex::init($environment, $merchantId, $merchantPrivateKey);
    }

    private static function init($environment, $merchantId, $merchantPrivateKey)
    {
        Log::debug(__METHOD__);

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
        Log::debug(__METHOD__);

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
     * @param $ticketArray
     *
     * @return array
     *
     * @throws BexException
     * @throws \Bex\exceptions\MerchantServiceException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createTicket($ticketArray)
    {
        Log::debug(__METHOD__);
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
        $builder->setInstallmentUrl(getValue($ticketArray, 'installmentUrl'));
        $builder->setNonceUrl(getValue($ticketArray, 'nonceUrl'));
        $builder->setCampaignCode(getValue($ticketArray, 'campaignCode'));
        $builder->setOrderId(getValue($ticketArray, 'orderId'));
//        $builder->setTckn(getValue($ticketArray, 'tckn'));
//        $builder->setMsisdn(getValue($ticketArray, 'msisdn'));
        $builder->setAddress(getValue($ticketArray, 'address'));
        $builder->setAgreementUrl(getValue($ticketArray, 'agreementUrl'));

        $ticketResponse = $this->merchantService->oneTimeTicketWithBuilder(
            $this->merchantLoginResponse->getToken(),
            $builder
        );

        return [
            'id' => $ticketResponse->getTicketShortId(),
            'path' => $ticketResponse->getTicketPath(),
            'token' => $ticketResponse->getTicketToken(),
        ];
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

    /**
     * @param $orderData
     * @return \Bex\merchant\response\RefundResponse
     * @throws BexException
     * @throws \Bex\exceptions\MerchantServiceException
     */
    public function refund($orderData)
    {
        $refundRequest = (new \Bex\merchant\request\RefundRequest())
            ->setUniqueReferans($orderData['orderId']) //random?
            ->setAmount($orderData['amount'])
            ->setTransactionToken($orderData['transactionToken'])
            ->setMerchantId(MERCHANT_ID)
            ->setRequestType(1)
            ->setCurrency(949)
            ->setTs(date('ymd-h:i:s'))
        ;

        $this->login();
        return $this->merchantService->refund($refundRequest, $this->merchantLoginResponse->getToken());
    }

    public function transactionList()
    {
        $transactionListRequest = new \Bex\merchant\request\transactions\TransactionListRequest(
            MERCHANT_ID,
            (new \DateTime('-1 week'))->format('Y-m-d H:i'),
            (new \DateTime())->format('Y-m-d H:i')
        );

        $this->login();
        return $this->merchantService->transactionList($transactionListRequest, $this->merchantLoginResponse->getToken());
    }
    public function transactionDetail($orderId, $ticket)
    {
        $transactionDetailRequest = new \Bex\merchant\request\transactions\TransactionDetailRequest(
            MERCHANT_ID,
            $ticket,
            $orderId
        );

        $this->login();
        return $this->merchantService->transactionDetail($transactionDetailRequest, $this->merchantLoginResponse->getToken());
    }

    public function approve(callable $callback)
    {
        //NULL CHECK
        $data = $this->takeDataAndRespond();
        if (null != $data) { // Data is ok.
            $this->login();

            $nonceRequest = new NonceRequest(
                $data['id'],
                $data['path'],
                $data['issuer'],
                $data['approver'],
                $data['token'],
                $data['signature'],
                $data['reply'],
                $data['reply']['hash'],
                $data['reply']['tcknMatch'],
                $data['reply']['msisdnMatch']
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
            if (EncryptionUtil::verifyBexSign($data['id'], $data['signature'])) {
                Log::debug(__METHOD__, [
                    'action' => 'Bex Sign Verified',
                ]);

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

                Log::debug(__METHOD__, [
                    'action' => 'Sending Nonce Response',
                ]);

                return $this->merchantService->sendNonceResponse(
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
                $merchantNonceResponse->setNonce($data['token']);
                $merchantNonceResponse->setId($data['id']);
                $merchantNonceResponse->setMessage('Signature verification failed');

                Log::debug(__METHOD__, [
                    'action' => 'Sending Nonce Response as Failed',
                ]);

                return $this->merchantService->sendNonceResponse(
                    $merchantNonceResponse,
                    $this->merchantLoginResponse->getPath(),
                    $data['path'],
                    $this->merchantLoginResponse->getConnectionToken(),
                    $data['token']
                );
            }
        }
    }

    /**
     * @return array|mixed
     */
    protected function takeDataAndRespond()
    {
        //ISTEKDEN GELEN REQUESTI ALIYORUZ.
        $data = json_decode(file_get_contents('php://input'), true);

        Log::debug(__METHOD__, (array) $data);

        //NULL CHECK
        if (null != $data) {
            //DONULMESI GEREKEN RESPONSE ORNEGI JSON FORMATINDA OLMALIDIR.
            header('Content-type: application/json');
            // NONCE ILK RESPONSE
            //ILK RESPONSE UN FORMATINA DIKKAT EDILMELIDIR.
            Log::debug(__METHOD__, [
                'action' => 'ob_start',
            ]);
            ob_start();
            ob_implicit_flush(true);
            ob_end_flush();
            echo json_encode([
                'result' => 'ok',
                'data' => 'ok',
            ]);
            Log::debug(__METHOD__, [
                'result' => 'ok',
                'data' => 'ok',
            ]);
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
            ob_end_clean();
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

            if (!isset($data['reply']['deliveryAddress'])) {
                $data['reply']['deliveryAddress'] = null;
            }
            if (!isset($data['reply']['billingAddress'])) {
                $data['reply']['billingAddress'] = null;
            }
            if (!isset($data['reply']['tcknMatch'])) {
                $data['reply']['tcknMatch'] = false;
            }
            if (!isset($data['reply']['msisdnMatch'])) {
                $data['reply']['msisdnMatch'] = false;
            }

            return $data;
        }

        return [];
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
