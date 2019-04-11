<?php

namespace Bex\merchant\response;

use Bex\merchant\request\VposConfig;

class Installment
{
    public $numberOfInstallment;
    public $installmentAmount;
    public $totalAmount;
    public $label; //if you not change the installment label you can set null or empty string

    public $subMerchantName;
    public $subMerchantId;
    public $subMerchantPostalCode;
    public $subMerchantCity;
    public $subMerchantCountry;

    public $vposConfig;

    /**
     * Installment constructor.
     *
     * @param $numberOfInstallment
     * @param $installmentAmount
     * @param $label
     * @param $totalAmount
     * @param VposConfig|null $vposConfig
     */
    public function __construct($numberOfInstallment, $installmentAmount, $label, $totalAmount, $vposConfig)
    {
        $this->numberOfInstallment = $numberOfInstallment;
        $this->installmentAmount = $installmentAmount;
        $this->label = $label;
        $this->totalAmount = $totalAmount;
        $this->vposConfig = $vposConfig;
    }

    /**
     * @return mixed
     */
    public function getNumberOfInstallment()
    {
        return $this->numberOfInstallment;
    }

    /**
     * @param mixed $numberOfInstallment
     */
    public function setNumberOfInstallment($numberOfInstallment)
    {
        $this->numberOfInstallment = $numberOfInstallment;
    }

    /**
     * @return mixed
     */
    public function getInstallmentAmount()
    {
        return $this->installmentAmount;
    }

    /**
     * @param mixed $installmentAmount
     */
    public function setInstallmentAmount($installmentAmount)
    {
        $this->installmentAmount = $installmentAmount;
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getVposConfig()
    {
        return $this->vposConfig;
    }

    /**
     * @param mixed $vposConfig
     */
    public function setVposConfig($vposConfig)
    {
        $this->vposConfig = $vposConfig;
    }

    /**
     * @return mixed
     */
    public function getSubMerchantName()
    {
        return $this->subMerchantName;
    }

    /**
     * @param mixed $subMerchantName
     */
    public function setSubMerchantName($subMerchantName)
    {
        $this->subMerchantName = $subMerchantName;
    }

    /**
     * @return mixed
     */
    public function getSubMerchantId()
    {
        return $this->subMerchantId;
    }

    /**
     * @param mixed $subMerchantId
     */
    public function setSubMerchantId($subMerchantId)
    {
        $this->subMerchantId = $subMerchantId;
    }

    /**
     * @return mixed
     */
    public function getSubMerchantPostalCode()
    {
        return $this->subMerchantPostalCode;
    }

    /**
     * @param mixed $subMerchantPostalCode
     */
    public function setSubMerchantPostalCode($subMerchantPostalCode)
    {
        $this->subMerchantPostalCode = $subMerchantPostalCode;
    }

    /**
     * @return mixed
     */
    public function getSubMerchantCity()
    {
        return $this->subMerchantCity;
    }

    /**
     * @param mixed $subMerchantCity
     */
    public function setSubMerchantCity($subMerchantCity)
    {
        $this->subMerchantCity = $subMerchantCity;
    }

    /**
     * @return mixed
     */
    public function getSubMerchantCountry()
    {
        return $this->subMerchantCountry;
    }

    /**
     * @param mixed $subMerchantCountry
     */
    public function setSubMerchantCountry($subMerchantCountry)
    {
        $this->subMerchantCountry = $subMerchantCountry;
    }
}
