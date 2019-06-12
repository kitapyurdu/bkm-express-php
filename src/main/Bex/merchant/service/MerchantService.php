<?php

namespace Bex\merchant\service;

use Bex\config\Configuration;
use Bex\exceptions\EncryptionException;
use Bex\exceptions\MerchantServiceException;
use Bex\merchant\request\Builder;
use Bex\merchant\request\MerchantLoginRequest;
use Bex\merchant\request\RefundRequest;
use Bex\merchant\request\TicketRequest;
use Bex\merchant\request\transactions\TransactionDetailRequest;
use Bex\merchant\request\transactions\TransactionListRequest;
use Bex\merchant\response\MerchantLoginResponse;
use Bex\merchant\response\nonce\MerchantNonceResponse;
use Bex\merchant\response\nonce\NonceResultResponse;
use Bex\merchant\response\RefundResponse;
use Bex\merchant\response\TicketResponse;
use Bex\merchant\response\TransactionResponse;
use Bex\merchant\security\EncryptionUtil;
use Bex\merchant\token\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MerchantService
{
    protected $configuration;

    /**
     * MerchantService constructor.
     *
     * @param $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return MerchantLoginResponse
     *
     * @throws MerchantServiceException
     * @throws EncryptionException
     * @throws GuzzleException
     */
    public function login()
    {
        $sign = EncryptionUtil::sign($this->configuration->getMerchantId(), $this->configuration->getMerchantPrivateKey());
        $merchantLoginRequest = new MerchantLoginRequest($this->configuration->getMerchantId(), $sign);
        $merchantId = $merchantLoginRequest->getName();
        $merchantSignature = $merchantLoginRequest->getPassword();
        $requestBody = $this->encodeMerchantLoginRequestObject($merchantId, $merchantSignature);
        $client = new Client();
        try {
            $res = $client->request('POST', $this->getMerchantLoginUrl(), $this->postRequestOptionsWithoutToken($requestBody));
            if (200 === $res->getStatusCode()) {
                $bodyData = json_decode($res->getBody()->getContents(), true);

                return new MerchantLoginResponse($bodyData['code'], $bodyData['call'], $bodyData['description'], $bodyData['message'], $bodyData['result'], $bodyData['parameters'], $bodyData['data']['id'], $bodyData['data']['path'], $bodyData['data']['token']);
            }
        } catch (GuzzleException $exception) {
            throw new MerchantServiceException($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @param $sign
     *
     * @return array
     */
    private function encodeMerchantLoginRequestObject($id, $sign)
    {
        return json_encode([
            'id' => $id,
            'signature' => $sign,
        ]);
    }

    /**
     * @return string
     */
    private function getMerchantLoginUrl()
    {
        return $this->configuration->getBexApiConfiguration()->getBaseUrl().'merchant/login';
    }

    /**
     * @return string
     */
    private function getMerchantTransactionListUrl()
    {
        return $this->configuration->getBexApiConfiguration()->getBaseUrl().'merchant/merchantTransactionList';
    }

    /**
     * @return string
     */
    private function getRefundRequestUrl()
    {
        return $this->configuration->getBexApiConfiguration()->getBaseWsUrl().'BKMExpressReversalRestService/reversalWithRef.do';
    }

    /**
     * @param $requestBody
     *
     * @return array
     */
    private function postRequestOptionsWithoutToken($requestBody)
    {
        return [
            'headers' => ['Content-Type' => 'application/json'],
            'curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2],
            'body' => $requestBody,
        ];
    }

    public function refund(RefundRequest $refundRequest, Token $token)
    {
        try {
            $refundRequest->setS(EncryptionUtil::sign($refundRequest->getSignString(), $this->configuration->getMerchantPrivateKey()));
            $client = new Client();
            $res = $client->request('POST', $this->getRefundRequestUrl(), $this->postRequestOptionsWithToken(json_encode($refundRequest), $token->getToken()));
            if (200 === $res->getStatusCode()) {
                $bodyData = json_decode($res->getBody()->getContents(), true);

//                dd($bodyData);
                return new RefundResponse($bodyData['result']['code'], $bodyData['uniqueReferans'], $bodyData['posResult'], @$bodyData['posResult']['orderId']);
            }
        } catch (GuzzleException $exception) {
            throw new MerchantServiceException($exception->getMessage());
        }
    }

    /**
     * @param TransactionListRequest $transactionListRequest
     * @param Token                  $token
     *
     * @return array|TransactionResponse[]
     *
     * @throws EncryptionException
     * @throws MerchantServiceException
     */
    public function transactionList(TransactionListRequest $transactionListRequest, Token $token)
    {
        try {
            $transactionListRequest->signature = (EncryptionUtil::sign($transactionListRequest->id, $this->configuration->getMerchantPrivateKey()));
            $client = new Client();
            $res = $client->request('POST', $this->getMerchantTransactionListUrl(), $this->postRequestOptionsWithToken(json_encode($transactionListRequest), $token->getToken()));

            if (200 === $res->getStatusCode()) {
                $bodyData = json_decode($res->getBody()->getContents(), true)['data'];

                $transactions = $bodyData['merchantTransactions'];
                $array = [];
                foreach ($transactions as $transaction) {
                    $array[] = (new TransactionResponse())
                        ->setTicket($transaction['ticket'])
                        ->setOrderId($transaction['orderId'])
                        ->setAmount($transaction['amount'])
                        ->setPaymentAmount($transaction['paymentAmount'])
                        ->setPaymentDate($transaction['paymentDate'])
                        ->setBankCode($transaction['bankCode'])
                        ->setVposBankCode($transaction['vposBankCode'])
                        ->setInstallment($transaction['installment'])
                        ->setPaymentResult($transaction['paymentResult'])
                        ->setAuthorizationCode($transaction['authorizationCode'])
                        ->setPosResponse($transaction['posResponse'])
                        ->setReferenceNumber($transaction['referenceNumber'])
                        ->setFailResultCode($transaction['failResultCode'])
                        ->setIsPreAuth($transaction['isPreAuth'])
                        ->setTcknHash($transaction['tcknHash'])
                        ->setFirst6digits($transaction['first6digits'])
                        ->setLast4digits($transaction['last4digits'])
                        ;
                }

                return $array;
            }
        } catch (GuzzleException $exception) {
            throw new MerchantServiceException($exception->getMessage());
        }
    }

    /**
     * @param TransactionDetailRequest $transactionDetailRequest
     * @param Token                    $token
     *
     * @return TransactionResponse
     *
     * @throws EncryptionException
     * @throws MerchantServiceException
     */
    public function transactionDetail(TransactionDetailRequest $transactionDetailRequest, Token $token)
    {
        try {
            $transactionDetailRequest->signature = (EncryptionUtil::sign($transactionDetailRequest->id, $this->configuration->getMerchantPrivateKey()));
            $client = new Client();
            $res = $client->request('POST', $this->getMerchantTransactionListUrl(), $this->postRequestOptionsWithToken(json_encode($transactionDetailRequest), $token->getToken()));

            if (200 === $res->getStatusCode()) {
                $bodyData = json_decode($res->getBody()->getContents(), true)['data'];

                $transaction = $bodyData['merchantTransactions'][0];

                return (new TransactionResponse())
                    ->setTicket($transaction['ticket'])
                    ->setOrderId($transaction['orderId'])
                    ->setAmount($transaction['amount'])
                    ->setPaymentAmount($transaction['paymentAmount'])
                    ->setPaymentDate($transaction['paymentDate'])
                    ->setBankCode($transaction['bankCode'])
                    ->setVposBankCode($transaction['vposBankCode'])
                    ->setInstallment($transaction['installment'])
                    ->setPaymentResult($transaction['paymentResult'])
                    ->setAuthorizationCode($transaction['authorizationCode'])
                    ->setPosResponse($transaction['posResponse'])
                    ->setReferenceNumber($transaction['referenceNumber'])
                    ->setFailResultCode($transaction['failResultCode'])
                    ->setIsPreAuth($transaction['isPreAuth'])
                    ->setTcknHash($transaction['tcknHash'])
                    ->setFirst6digits($transaction['first6digits'])
                    ->setLast4digits($transaction['last4digits'])
                    ;
            }
        } catch (GuzzleException $exception) {
            throw new MerchantServiceException($exception->getMessage());
        }
    }

    /**
     * @param Token $connection
     * @param $amount
     * @param $installmentUrl
     *
     * @return TicketResponse
     *
     * @throws MerchantServiceException
     * @throws GuzzleException
     */
    public function oneTimeTicket(Token $connection, $amount, $installmentUrl)
    {
        $builder = new Builder('payment');
        $builder->setAmount($amount);
        $builder->setInstallmentUrl($installmentUrl);
        $requestBody = Builder::newPayment('payment')->amountAndInstallmentUrl($builder);

        return $this->createOneTimeTicket($requestBody, $connection);
    }

    /**
     * @param TicketRequest $requestBody
     * @param Token         $token
     *
     * @return TicketResponse
     *
     * @throws MerchantServiceException
     * @throws GuzzleException
     */
    private function createOneTimeTicket($requestBody, Token $token)
    {
        $requestBody = $this->encodeTicketRequestObjectToJson($requestBody);
        try {
            $client = new Client();
            $res = $client->request('POST', $this->getMerchantCreateTicketUrl($token->getPath()), $this->postRequestOptionsWithToken($requestBody, $token->getToken()));
            if (200 === $res->getStatusCode()) {
                $bodyData = json_decode($res->getBody()->getContents(), true);

                return new TicketResponse($bodyData['code'], $bodyData['call'], $bodyData['description'], $bodyData['message'], $bodyData['result'], $bodyData['parameters'], $bodyData['data']['id'], $bodyData['data']['path'], $bodyData['data']['token']);
            }
        } catch (GuzzleException $exception) {
            throw new MerchantServiceException($exception->getMessage());
        }
    }

    /**
     * @param $ticketRequest
     *
     * @return string
     */
    private function encodeTicketRequestObjectToJson(TicketRequest $ticketRequest)
    {
        $amount = null != $ticketRequest->getAmount() ? $ticketRequest->getAmount() : '';
        $nonceUrl = null != $ticketRequest->getNonceUrl() ? $ticketRequest->getNonceUrl() : '';
        $installmentUrl = null != $ticketRequest->getInstallmentUrl() ? $ticketRequest->getInstallmentUrl() : '';
        $orderId = null != $ticketRequest->getOrderId() ? $ticketRequest->getOrderId() : '';
        $tckn = null != $ticketRequest->getTckn() ? $ticketRequest->getTckn() : '';
        $msisdn = null != $ticketRequest->getMsisdn() ? $ticketRequest->getMsisdn() : '';
        $campaignCode = null != $ticketRequest->getCampaignCode() ? $ticketRequest->getCampaignCode() : '';
        $address = null != $ticketRequest->getAddress() ? $ticketRequest->getAddress() : false;
        $agreementUrl = null != $ticketRequest->getAgreementUrl() ? $ticketRequest->getAgreementUrl() : '';
        $jsonArray = [
            'amount' => $amount,
            'nonceUrl' => $nonceUrl,
            'address' => $address,
        ];

        if (isset($installmentUrl) && strlen($installmentUrl) > 0) {
            $jsonArray['installmentUrl'] = $installmentUrl;
        }
        if (isset($orderId) && strlen($orderId) > 0) {
            $jsonArray['orderId'] = $orderId;
        }
        if (isset($tckn) && isset($tckn['no']) && strlen($tckn['no']) > 0) {
            $jsonArray['tckn'] = $tckn;
        }
        if (isset($msisdn) && isset($msisdn['no']) && strlen($msisdn['no']) > 0) {
            $jsonArray['msisdn'] = $msisdn;
        }
        if (isset($campaignCode) && strlen($campaignCode) > 0) {
            $jsonArray['campaignCode'] = $campaignCode;
        }

        if (isset($agreementUrl) && strlen($agreementUrl) > 0) {
            $jsonArray['agreementUrl'] = $agreementUrl;
        }

        return json_encode($jsonArray);
    }

    /**
     * @param $merchantPath
     *
     * @return string
     */
    private function getMerchantCreateTicketUrl($merchantPath)
    {
        return $this->configuration->getBexApiConfiguration()->getBaseUrl().'merchant/'.$merchantPath.'/ticket?type=payment';
    }

    /**
     * @param $requestBody
     * @param $token
     *
     * @return array
     */
    private function postRequestOptionsWithToken($requestBody, $token)
    {
        return [
            'headers' => ['Content-Type' => 'application/json', 'Bex-Connection' => $token],
            'curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2],
            'body' => $requestBody,
        ];
    }

    /**
     * @param Token $connection
     * @param $amount
     * @param $installmentUrl
     * @param $nonceUrl
     *
     * @return TicketResponse
     *
     * @throws MerchantServiceException
     * @throws GuzzleException
     */
    public function oneTimeTicketWithNonce(Token $connection, $amount, $installmentUrl, $nonceUrl)
    {
        $builder = new Builder('payment');
        $builder->setAmount($amount);
        $builder->setInstallmentUrl($installmentUrl);
        $builder->setNonceUrl($nonceUrl);
        $requestBody = Builder::newPayment('payment')->amountAndInstallmentUrl($builder);

        return $this->createOneTimeTicket($requestBody, $connection);
    }

    /**
     * @param Token $connection
     * @param $amount
     *
     * @return TicketResponse
     *
     * @throws MerchantServiceException
     * @throws GuzzleException
     */
    public function oneTimeTicketWithoutInstallmentUrl(Token $connection, $amount)
    {
        $builder = new Builder('payment');
        $builder->setAmount($amount);

        $requestBody = Builder::newPayment('payment')->amountAndInstallmentUrl($builder);

        return $this->createOneTimeTicket($requestBody, $connection);
    }

    /**
     * @param Token $connection
     * @param $amount
     * @param $nonceUrl
     *
     * @return TicketResponse
     *
     * @throws MerchantServiceException
     * @throws GuzzleException
     */
    public function oneTimeTicketWithoutInstallmentUrlWithNonce(Token $connection, $amount, $nonceUrl)
    {
        $builder = new Builder('payment');
        $builder->setAmount($amount);
        $builder->setNonceUrl($nonceUrl);
        $requestBody = Builder::newPayment('payment')->amountAndInstallmentUrl($builder);

        return $this->createOneTimeTicket($requestBody, $connection);
    }

    /**
     * @param Token   $connection
     * @param Builder $builder
     *
     * @return TicketResponse
     *
     * @throws MerchantServiceException
     * @throws GuzzleException
     */
    public function oneTimeTicketWithBuilder(Token $connection, Builder $builder)
    {
        $requestBody = $builder;

        return $this->createOneTimeTicket($requestBody, $connection);
    }

    /**
     * @param $token
     *
     * @return array
     */
    public function getRequestOptions($token)
    {
        return [
            'headers' => ['Content-Type' => 'application/json', 'Bex-Connection' => $token],
            'curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2],
        ];
    }

    /**
     * @param MerchantNonceResponse $response
     * @param $connectionId
     * @param $ticketId
     * @param $connectionToken
     * @param $nonceToken
     *
     * @return NonceResultResponse
     *
     * @throws MerchantServiceException
     * @throws GuzzleException
     */
    public function sendNonceResponse(MerchantNonceResponse $response, $connectionId, $ticketId, $connectionToken, $nonceToken)
    {
        return $this->nonce($response, $connectionId, $ticketId, $connectionToken, $nonceToken);
    }

    /**
     * @param MerchantNonceResponse $requestBody
     * @param $connectionId
     * @param $ticketId
     * @param $connectionToken
     * @param $nonceToken
     *
     * @return NonceResultResponse
     *
     * @throws MerchantServiceException
     * @throws GuzzleException
     */
    public function nonce(MerchantNonceResponse $requestBody, $connectionId, $ticketId, $connectionToken, $nonceToken)
    {
        $requestBody = $this->encodeMerchantNonceRequestObjectToJson($requestBody);

        try {
            $client = new Client();
            $request = $client->request(
                'POST',
                $this->getMerchantNonceUrl($connectionId, $ticketId),
                $this->postRequestOptionsWithNonceTokenAndToken($requestBody, $connectionToken, $nonceToken)
            );

            if (200 === $request->getStatusCode()) {
                $bodyData = json_decode($request->getBody()->getContents(), true);

                return new NonceResultResponse(
                    $bodyData['code'],
                    $bodyData['call'],
                    $bodyData['description'],
                    $bodyData['message'],
                    $bodyData['result'],
                    $bodyData['parameters'],
                    $bodyData['data']['bkmTokenId'],
                    $bodyData['data']['totalAmount'],
                    $bodyData['data']['installmentCount'],
                    $bodyData['data']['cardFirst6'],
                    $bodyData['data']['cardLast4'],
                    $bodyData['data']['paymentPurchased'],
                    $bodyData['data']['status'],
                    $bodyData['data']['posResult'],
                    $bodyData['data']['cardHash'],
                    array_key_exists('error', $bodyData['data']) ? $bodyData['data']['error'] : null
                );
            }
        } catch (GuzzleException $exception) {
            throw new MerchantServiceException($exception->getMessage());
        }
    }

    /**
     * @param MerchantNonceResponse $merchantNonceResponse
     *
     * @return string
     */
    private function encodeMerchantNonceRequestObjectToJson(MerchantNonceResponse $merchantNonceResponse)
    {
        return json_encode([
            'result' => $merchantNonceResponse->getResult(),
            'nonce' => $merchantNonceResponse->getNonce(),
            'id' => $merchantNonceResponse->getId(),
            'message' => $merchantNonceResponse->getMessage(),
        ]);
    }

    /**
     * @param $connectionId
     * @param $ticketId
     *
     * @return string
     */
    private function getMerchantNonceUrl($connectionId, $ticketId)
    {
        return $this->configuration->getBexApiConfiguration()->getBaseUrl().'merchant/'.$connectionId.'/ticket/'.$ticketId.'/operate?name=commit';
    }

    /**
     * @param string $requestBody
     * @param $connectionToken
     * @param $nonceToken
     *
     * @return array
     */
    private function postRequestOptionsWithNonceTokenAndToken($requestBody, $connectionToken, $nonceToken)
    {
        return [
            'headers' => ['Content-Type' => 'application/json', 'Bex-Connection' => $connectionToken, 'Bex-Nonce' => $nonceToken],
            'curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2],
            'body' => $requestBody,
        ];
    }
}
