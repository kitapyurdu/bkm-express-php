<?php

namespace Bex\config;

use Bex\exceptions\BexPaymentException;

class BexPayment
{
    /**
     * @param $environment
     * @param $merchantId
     * @param $merchantSecret
     *
     * @return Configuration
     *
     * @throws BexPaymentException
     */
    public function BexPayment($environment, $merchantId, $merchantSecret)
    {
        if (empty($environment)) {
            throw new BexPaymentException('Enviroment can not be NULL or Empty.');
        }
        if (empty($merchantId)) {
            throw new BexPaymentException('Merchant id can not be NULL or Empty.');
        }
        if (empty($merchantSecret)) {
            throw new BexPaymentException('Merchant Private Key can not be NULL or Empty.');
        }

        return new Configuration($environment, $merchantId, $merchantSecret);
    }

    /**
     * @param $environment
     * @param $merchantId
     * @param $merchantSecret
     *
     * @return Configuration
     *
     * @throws BexPaymentException
     */
    public static function startBexPayment($environment, $merchantId, $merchantSecret)
    {
        if (empty($environment)) {
            throw new BexPaymentException('Enviroment can not be NULL or Empty.');
        }
        if (empty($merchantId)) {
            throw new BexPaymentException('Merchant id can not be NULL or Empty.');
        }
        if (empty($merchantSecret)) {
            throw new BexPaymentException('Merchant Private Key can not be NULL or Empty.');
        }

        return new Configuration($environment, $merchantId, $merchantSecret);
    }
}
