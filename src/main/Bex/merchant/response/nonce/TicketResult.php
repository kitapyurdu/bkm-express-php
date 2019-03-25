<?php

namespace Bex\merchant\response\nonce;

class TicketResult
{
    private $bkmTokenId;
    private $totalAmount;
    private $installmentCount;
    private $cardFirst6;
    private $cardLast4;

    /**
     * TicketResult constructor.
     *
     * @param $bkmTokenId
     * @param $totalAmount
     * @param $installmentCount
     * @param $cardFirst6
     * @param $cardLast4
     */
    public function __construct($ticket)
    {
        $this->bkmTokenId = $ticket['bkmTokenId'];
        $this->totalAmount = $ticket['totalAmount'];
        $this->installmentCount = $ticket['installmentCount'];
        $this->cardFirst6 = $ticket['cardFirst6'];
        $this->cardLast4 = $ticket['cardLast4'];
    }

    /**
     * @return mixed
     */
    public function getBkmTokenId()
    {
        return $this->bkmTokenId;
    }

    /**
     * @param mixed $bkmTokenId
     */
    public function setBkmTokenId($bkmTokenId)
    {
        $this->bkmTokenId = $bkmTokenId;
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
    public function getInstallmentCount()
    {
        return $this->installmentCount;
    }

    /**
     * @param mixed $installmentCount
     */
    public function setInstallmentCount($installmentCount)
    {
        $this->installmentCount = $installmentCount;
    }

    /**
     * @return mixed
     */
    public function getCardFirst6()
    {
        return $this->cardFirst6;
    }

    /**
     * @param mixed $cardFirst6
     */
    public function setCardFirst6($cardFirst6)
    {
        $this->cardFirst6 = $cardFirst6;
    }

    /**
     * @return mixed
     */
    public function getCardLast4()
    {
        return $this->cardLast4;
    }

    /**
     * @param mixed $cardLast4
     */
    public function setCardLast4($cardLast4)
    {
        $this->cardLast4 = $cardLast4;
    }
}
