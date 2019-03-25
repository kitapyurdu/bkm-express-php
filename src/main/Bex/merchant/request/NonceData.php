<?php

namespace Bex\merchant\request;

use Bex\merchant\request\address\Address;

class NonceData
{
    private $ticketId;
    private $orderId;
    private $totalAmount;
    private $totalAmountWithInstallmentCharge;
    private $numberOfInstallments;
    private $deliveryAddress;
    private $billingAddress;
    private $hash;

    /**
     * NonceData constructor.
     *
     * @param $reply
     */
    public function __construct($reply)
    {
        $this->ticketId = $reply['ticketId'];
        $this->orderId = $reply['orderId'];
        $this->totalAmount = $reply['totalAmount'];
        $this->totalAmountWithInstallmentCharge = $reply['totalAmountWithInstallmentCharge'];
        $this->numberOfInstallments = $reply['numberOfInstallments'];
        $this->hash = $reply['hash'];
        $this->deliveryAddress = new Address($reply['deliveryAddress']);
        $this->billingAddress = new Address($reply['billingAddress']);

        return $this;
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
    public function getTotalAmountWithInstallmentCharge()
    {
        return $this->totalAmountWithInstallmentCharge;
    }

    /**
     * @param mixed $totalAmountWithInstallmentCharge
     */
    public function setTotalAmountWithInstallmentCharge($totalAmountWithInstallmentCharge)
    {
        $this->totalAmountWithInstallmentCharge = $totalAmountWithInstallmentCharge;
    }

    /**
     * @return mixed
     */
    public function getNumberOfInstallments()
    {
        return $this->numberOfInstallments;
    }

    /**
     * @param mixed $numberOfInstallments
     */
    public function setNumberOfInstallments($numberOfInstallments)
    {
        $this->numberOfInstallments = $numberOfInstallments;
    }

    /**
     * @return mixed
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * @param mixed $deliveryAddress
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @return mixed
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param mixed $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }
}
