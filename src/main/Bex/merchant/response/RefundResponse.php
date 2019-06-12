<?php

namespace Bex\merchant\response;

class RefundResponse
{
    public $uniqueReferans;

    public $ts;

    public $s;

    public $code;

    public $message;

    /**
     * @var array
     */
    public $posResult = [];

    public $orderId;

    public $authCode;

    public function __construct($code, $uniqueReferans, $posResult, $orderId)
    {
        $this->code = (int) $code;
        $this->uniqueReferans = $uniqueReferans;
        $this->orderId = $orderId;
        $this->posResult = (array) $posResult;
    }

    /**
     * @return mixed
     */
    public function getUniqueReferans()
    {
        return $this->uniqueReferans;
    }

    /**
     * @return mixed
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * @return mixed
     */
    public function getS()
    {
        return $this->s;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getPosResult()
    {
        return $this->PosResult;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return mixed
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * @return mixed
     */
    public function getPosResponse()
    {
        return $this->posResponse;
    }

    /**
     * @return mixed
     */
    public function getPosResultCode()
    {
        return $this->posResultCode;
    }

    /**
     * @return mixed
     */
    public function getPostResultMessage()
    {
        return $this->postResultMessage;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return 0 === $this->getCode();
    }
}
