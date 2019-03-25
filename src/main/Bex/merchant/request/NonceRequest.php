<?php

namespace Bex\merchant\request;

class NonceRequest
{
    private $id;
    private $path;
    private $issuer;
    private $approver;
    private $token;
    private $signature;
    private $reply;
    private $hash;
    private $tcknMath;
    private $msisdnMatch;

    /**
     * NonceRequest constructor.
     *
     * @param $id
     * @param $path
     * @param $issuer
     * @param $approver
     * @param $token
     * @param $signature
     * @param $reply
     * @param $hash
     * @param $tcknMatch
     * @param $msisdnMatch
     */
    public function __construct($id, $path, $issuer, $approver, $token, $signature, $reply, $hash, $tcknMatch, $msisdnMatch)
    {
        $this->id = $id;
        $this->path = $path;
        $this->issuer = $issuer;
        $this->approver = $approver;
        $this->token = $token;
        $this->signature = $signature;
        $this->reply = $reply;
        $this->reply = new NonceData($reply);
        $this->hash = $hash;
        $this->tcknMath = $tcknMatch;
        $this->msisdnMatch = $msisdnMatch;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTicketId()
    {
        return $this->reply->getTicketId();
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->reply->getOrderId();
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->reply->getTotalAmount();
    }

    /**
     * @return mixed
     */
    public function getNumberOfInstallments()
    {
        return $this->reply->getNumberOfInstallments();
    }

    /**
     * @return mixed
     */
    public function getTotalAmountWithInstallmentCharge()
    {
        return $this->reply->getTotalAmountWithInstallmentCharge();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * @param mixed $issuer
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
    }

    /**
     * @return mixed
     */
    public function getApprover()
    {
        return $this->approver;
    }

    /**
     * @param mixed $approver
     */
    public function setApprover($approver)
    {
        $this->approver = $approver;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * @param mixed $reply
     */
    public function setReply($reply)
    {
        $this->reply = $reply;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getTcknMath()
    {
        return $this->tcknMath;
    }

    /**
     * @param mixed $tcknMath
     */
    public function setTcknMath($tcknMath)
    {
        $this->tcknMath = $tcknMath;
    }

    /**
     * @return mixed
     */
    public function getMsisdnMatch()
    {
        return $this->msisdnMatch;
    }

    /**
     * @param mixed $msisdnMatch
     */
    public function setMsisdnMatch($msisdnMatch)
    {
        $this->msisdnMatch = $msisdnMatch;
    }

    /**
     * @return mixed
     */
    public function getDeliveryAddress()
    {
        return $this->reply->getDeliveryAddress();
    }

    /**
     * @param mixed $deliveryAddress
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->reply->setDeliveryAddress($deliveryAddress);
    }

    /**
     * @return mixed
     */
    public function getBillingAddress()
    {
        return $this->reply->getBillingAddress();
    }

    /**
     * @param mixed $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->reply->setBillingAddress($billingAddress);
    }
}
