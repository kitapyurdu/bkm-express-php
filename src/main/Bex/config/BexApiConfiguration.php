<?php

namespace Bex\config;

use Bex\enums\Environment;
use Bex\exceptions\BexApiConfigurationException;

class BexApiConfiguration
{
    const LOCAL_URL = 'http://api.bex.dev/v1/';
    const DEV_URL = 'https://bex-api.finartz.com/v1/';
    const SANDBOX_URL = 'https://test-api.bkmexpress.com.tr/v1/';
    const PREPROD_URL = 'https://preprod-api.bkmexpress.com.tr/v1/';
    const PRODUCTION_URL = 'https://api.bkmexpress.com.tr/v1/';

    const LOCAL_EXPRESS_JS_URL = 'http://js.bex.dev/javascripts/bex.js';
    const DEV_EXPRESS_JS_URL = 'https://bex-js.finartz.com/v1/javascripts/bex.js';
    const SANDBOX_EXPRESS_JS_URL = 'https://test-js.bkmexpress.com.tr/v1/javascripts/bex.js';
    const PREPROD_EXPRESS_JS_URL = 'https://preprod-js.bkmexpress.com.tr/v1/javascripts/bex.js';
    const PRODUCTION_EXPRESS_JS_URL = 'https://js.bkmexpress.com.tr/v1/javascripts/bex.js';

    private $baseUrl;
    private $baseJs;

    /**
     * BexApiConfiguration constructor.
     *
     * @param mixed $environment
     */
    public function __construct($environment)
    {
        if (Environment::LOCAL == $environment) {
            $this->baseUrl = BexApiConfiguration::LOCAL_URL;
            $this->baseJs = BexApiConfiguration::LOCAL_EXPRESS_JS_URL;
        } elseif (Environment::DEV == $environment) {
            $this->baseUrl = BexApiConfiguration::DEV_URL;
            $this->baseJs = BexApiConfiguration::DEV_EXPRESS_JS_URL;
        } elseif (Environment::SANDBOX == $environment) {
            $this->baseUrl = BexApiConfiguration::SANDBOX_URL;
            $this->baseJs = BexApiConfiguration::SANDBOX_EXPRESS_JS_URL;
        } elseif (Environment::PREPROD == $environment) {
            $this->baseUrl = BexApiConfiguration::PREPROD_URL;
            $this->baseJs = BexApiConfiguration::PREPROD_EXPRESS_JS_URL;
        } elseif (Environment::PRODUCTION == $environment) {
            $this->baseUrl = BexApiConfiguration::PRODUCTION_URL;
            $this->baseJs = BexApiConfiguration::PRODUCTION_EXPRESS_JS_URL;
        } else {
            throw new BexApiConfigurationException('You should set a valid environment');
        }
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param mixed $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return mixed
     */
    public function getBaseJs()
    {
        return $this->baseJs;
    }

    /**
     * @param mixed $baseJs
     */
    public function setBaseJs($baseJs)
    {
        $this->baseJs = $baseJs;
    }
}
