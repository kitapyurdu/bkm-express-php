<?php

namespace Bex\merchant\response;

class TransactionResponse
{
    /**
     * @var string
     */
    public $ticket;

    /**
     * @var string
     */
    public $orderId;

    /**
     * "285,57".
     *
     * @var string
     */
    public $amount;

    /**
     * "285,57".
     *
     * @var string
     */
    public $paymentAmount;

    /**
     * @var \DateTimeInterface
     */
    public $paymentDate;

    /**
     * @var string
     */
    public $bankCode;

    /**
     * @var string
     */
    public $vposBankCode;

    /**
     * @var int
     */
    public $installment;

    /**
     * @var int
     */
    public $paymentResult;

    /**
     * @var string
     */
    public $authorizationCode;

    /**
     * @var string|json
     */
    public $posResponse;

    /**
     * @var string
     */
    public $referenceNumber;

    /**
     * @var string
     */
    public $failResultCode;

    /**
     * @var bool
     */
    public $isPreAuth;

    /**
     * @var string
     */
    public $tcknHash;

    /**
     * @var string
     */
    public $first6digits;

    /**
     * @var string
     */
    public $last4digits;

    /**
     * @return mixed
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param mixed $ticket
     *
     * @return TransactionResponse
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     *
     * @return TransactionResponse
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     *
     * @return TransactionResponse
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * @param mixed $paymentAmount
     *
     * @return TransactionResponse
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @param mixed $paymentDate
     *
     * @return TransactionResponse
     *
     * @throws \Exception
     */
    public function setPaymentDate($paymentDate)
    {
        if ($paymentDate) {
            $paymentDate = new \DateTime($paymentDate);
        }
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * @param mixed $bankCode
     *
     * @return TransactionResponse
     */
    public function setBankCode($bankCode)
    {
        $this->bankCode = $bankCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVposBankCode()
    {
        return $this->vposBankCode;
    }

    /**
     * @param mixed $vposBankCode
     *
     * @return TransactionResponse
     */
    public function setVposBankCode($vposBankCode)
    {
        $this->vposBankCode = $vposBankCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallment()
    {
        return $this->installment;
    }

    /**
     * @param mixed $installment
     *
     * @return TransactionResponse
     */
    public function setInstallment($installment)
    {
        $this->installment = (int) $installment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentResult()
    {
        return $this->paymentResult;
    }

    /**
     * @param mixed $paymentResult
     *
     * @return TransactionResponse
     */
    public function setPaymentResult($paymentResult)
    {
        $this->paymentResult = (int) $paymentResult;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param mixed $authorizationCode
     *
     * @return TransactionResponse
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosResponse()
    {
        return $this->posResponse;
    }

    /**
     * @param mixed $posResponse
     *
     * @return TransactionResponse
     */
    public function setPosResponse($posResponse)
    {
        try {
            $posResponse = json_decode($posResponse, true);
        } catch (\Exception $exception) {
        }
        $this->posResponse = $posResponse;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * @param mixed $referenceNumber
     *
     * @return TransactionResponse
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFailResultCode()
    {
        return $this->failResultCode;
    }

    /**
     * @param mixed $failResultCode
     *
     * @return TransactionResponse
     */
    public function setFailResultCode($failResultCode)
    {
        $this->failResultCode = $failResultCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsPreAuth()
    {
        return $this->isPreAuth;
    }

    /**
     * @param mixed $isPreAuth
     *
     * @return TransactionResponse
     */
    public function setIsPreAuth($isPreAuth)
    {
        if ('true' === $isPreAuth) {
            $isPreAuth = true;
        } elseif ('false' === $isPreAuth) {
            $isPreAuth = false;
        }
        $this->isPreAuth = $isPreAuth;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTcknHash()
    {
        return $this->tcknHash;
    }

    /**
     * @param mixed $tcknHash
     *
     * @return TransactionResponse
     */
    public function setTcknHash($tcknHash)
    {
        $this->tcknHash = $tcknHash;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirst6digits()
    {
        return $this->first6digits;
    }

    /**
     * @param mixed $first6digits
     *
     * @return TransactionResponse
     */
    public function setFirst6digits($first6digits)
    {
        $this->first6digits = $first6digits;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLast4digits()
    {
        return $this->last4digits;
    }

    /**
     * @param mixed $last4digits
     *
     * @return TransactionResponse
     */
    public function setLast4digits($last4digits)
    {
        $this->last4digits = $last4digits;

        return $this;
    }
}
