<?php

namespace Bex\config;

use Bex\enums\Environment;

class Configuration
{
    private $environment;
    private $merchantId;
    private $merchantPrivateKey;
    private $bexApiConfiguration;

    /**
     * Configuration constructor.
     *
     * @param $environment
     * @param $merchantId
     * @param $merchantPrivateKey
     */
    public function __construct($environment, $merchantId, $merchantPrivateKey)
    {
        $this->environment = $environment;
        $this->merchantId = $merchantId;
        $this->merchantPrivateKey = $merchantPrivateKey;
        $this->bexApiConfiguration = new BexApiConfiguration($environment);
    }

    /**
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param Environment $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param mixed $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @return mixed
     */
    public function getMerchantPrivateKey()
    {
        return $this->merchantPrivateKey;
    }

    /**
     * @param mixed $merchantPrivateKey
     */
    public function setMerchantPrivateKey($merchantPrivateKey)
    {
        $this->merchantPrivateKey = $merchantPrivateKey;
    }

    /**
     * @return BexApiConfiguration
     */
    public function getBexApiConfiguration()
    {
        return $this->bexApiConfiguration;
    }

    /**
     * @param mixed $bexApiConfiguration
     */
    public function setBexApiConfiguration($bexApiConfiguration)
    {
        $this->bexApiConfiguration = $bexApiConfiguration;
    }
}
