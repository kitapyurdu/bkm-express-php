<?php

namespace Bex\merchant\request;

class InstallmentRequest
{
    private $binNo;
    private $totalAmount;
    private $ticketId;
    private $signature;

    /**
     * InstallmentRequest constructor.
     *
     * @param $binNo
     * @param $totalAmount
     * @param $ticketId
     * @param $signature
     */
    public function __construct($binNo, $totalAmount, $ticketId, $signature)
    {
        $this->binNo = $binNo;
        $this->totalAmount = $totalAmount;
        $this->ticketId = $ticketId;
        $this->signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getBinNo()
    {
        return $this->binNo;
    }

    /**
     * @param mixed $binNo
     */
    public function setBinNo($binNo)
    {
        $this->binNo = $binNo;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param mixed $totalAmount
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return mixed
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }

    /**
     * @param mixed $ticketId
     */
    public function setTicketId($ticketId)
    {
        $this->ticketId = $ticketId;
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
}
