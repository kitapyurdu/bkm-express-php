<?php

namespace Bex\merchant\request;

class VposConfig
{
    public $vposUserId;
    public $vposPassword;
    public $extra = [];
    public $bankIndicator;
    public $serviceUrl;
    public $preAuth;

    public function addSubMerchantForFinans($subMerchantName, $subMerchantId, $subMerchantPostalCode, $subMerchantCity, $subMerchantCountry)
    {
        $this->addExtra('subMerchantName', $subMerchantName);
        $this->addExtra('subMerchantId', $subMerchantId);
        $this->addExtra('subMerchantPostalCode', $subMerchantPostalCode);
        $this->addExtra('subMerchantCity', $subMerchantCity);
        $this->addExtra('subMerchantCountry', $subMerchantCountry);

        return $this;
    }

    public function addSubMerchant($subMerchantName)
    {
        $this->addExtra('subMerchantName', $subMerchantName);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVposUserId()
    {
        return $this->vposUserId;
    }

    /**
     * @param mixed $vposUserId
     */
    public function setVposUserId($vposUserId)
    {
        $this->vposUserId = $vposUserId;
    }

    /**
     * @return mixed
     */
    public function getVposPassword()
    {
        return $this->vposPassword;
    }

    /**
     * @param mixed $vposPassword
     */
    public function setVposPassword($vposPassword)
    {
        $this->vposPassword = $vposPassword;
    }

    /**
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * @return mixed
     */
    public function getBankIndicator()
    {
        return $this->bankIndicator;
    }

    /**
     * @param mixed $bankIndicator
     */
    public function setBankIndicator($bankIndicator)
    {
        $this->bankIndicator = $bankIndicator;
    }

    /**
     * @return mixed
     */
    public function getServiceUrl()
    {
        return $this->serviceUrl;
    }

    /**
     * @param mixed $serviceUrl
     */
    public function setServiceUrl($serviceUrl)
    {
        $this->serviceUrl = $serviceUrl;
    }

    public function addExtra($key, $value)
    {
        $this->extra[$key] = $value;
    }

    /**
     * @return mixed
     */
    public function getPreAuth()
    {
        return $this->preAuth;
    }

    /**
     * @param mixed $preAuth
     */
    public function setPreAuth($preAuth)
    {
        $this->preAuth = $preAuth;
    }
}
