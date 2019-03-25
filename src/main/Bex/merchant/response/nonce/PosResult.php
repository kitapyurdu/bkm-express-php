<?php
/**
 * Created by IntelliJ IDEA.
 * User: mrpehlivan
 * Date: 20/01/2017
 * Time: 14:24.
 */

namespace Bex\merchant\response\nonce;

class PosResult
{
    private $orderId;
    private $authCode;
    private $posResponse;
    private $posResultCode;
    private $referenceNumber;
    private $posTransactionId;
    private $posBank;

    /**
     * PosResult constructor.
     *
     * @param $orderId
     * @param $authCode
     * @param $posResponse
     * @param $posResultCode
     * @param $referenceNumber
     * @param $posTransactionId
     * @param $posBank
     */
    public function __construct($posResult)
    {
        $this->orderId = $posResult['orderId'];
        $this->authCode = $posResult['authCode'];
        $this->posResponse = $posResult['posResponse'];
        $this->posResultCode = $posResult['posResultCode'];
        $this->referenceNumber = $posResult['referenceNumber'];
        $this->posTransactionId = $posResult['posTransactionId'];
        $this->posBank = $posResult['posBank'];
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
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * @param mixed $authCode
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;
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
     */
    public function setPosResponse($posResponse)
    {
        $this->posResponse = $posResponse;
    }

    /**
     * @return mixed
     */
    public function getPosResultCode()
    {
        return $this->posResultCode;
    }

    /**
     * @param mixed $posResultCode
     */
    public function setPosResultCode($posResultCode)
    {
        $this->posResultCode = $posResultCode;
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
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;
    }

    /**
     * @return mixed
     */
    public function getPosTransactionId()
    {
        return $this->posTransactionId;
    }

    /**
     * @param mixed $posTransactionId
     */
    public function setPosTransactionId($posTransactionId)
    {
        $this->posTransactionId = $posTransactionId;
    }

    /**
     * @return mixed
     */
    public function getPosBank()
    {
        return $this->posBank;
    }

    /**
     * @param mixed $posBank
     */
    public function setPosBank($posBank)
    {
        $this->posBank = $posBank;
    }
}
