<?php

namespace Bex\merchant\response;

class RefundResponse
{
    public $uniqueReferans;

    public $ts;

    public $s;

    public $code;

    public $codeMsg;

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
        $this->setCodeMsgByCode($this->code);
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
        return $this->posResult;
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
     * @return bool
     */
    public function isSuccess()
    {
        return 0 === $this->getCode();
    }

    /**
     * @param int $code
     * @return string
     */
    private function setCodeMsgByCode($code)
    {
        switch ($code) {
            case 0:
                return 'Success';
            case 1:
                return 'System Error';
            case 2:
                return 'Invalid Input Parameters';
            case 3:
                return 'Time Synchronization Error';
            case 4:
                return 'MAC verification Failed';
            case 5:
                return 'Undefined merchant id';
            case 6:
                return 'NonUnique Reference Id';
            case 7:
                return 'Virtual Pos Error';
            case 8:
                return 'Invalid Acquirer Bank ID';
            case 9:
                return 'Invalid Transaction Token';
            default:
                return 'Unknown';
        }

    }
}
