<?php

namespace BexTest;

date_default_timezone_set('UTC');

use Bex\config\BexPayment;
use Bex\config\Configuration;
use Bex\enums\Environment;
use Bex\exceptions\BexPaymentException;
use PHPUnit_Framework_TestCase;

class Setup extends PHPUnit_Framework_TestCase
{
    const enviroment = Environment::PREPROD;

    public function __construct()
    {
        require_once __DIR__.'/../../samples/operations/config.php';

        self::integrationMerchantConfig();
    }

    /**
     * @return Configuration
     *
     * @throws BexPaymentException
     */
    public static function integrationMerchantConfig()
    {
        return BexPayment::startBexPayment(self::enviroment, MERCHANT_ID, PRIVATE_KEY);
    }

    public function testStartPayment()
    {
        self::assertEquals(self::integrationMerchantConfig(), BexPayment::startBexPayment(self::enviroment, MERCHANT_ID, PRIVATE_KEY));
    }

    public function testNotStartPaymentAndThrowExceptionUnknownEnviroment()
    {
        try {
            BexPayment::startBexPayment(null, MERCHANT_ID, PRIVATE_KEY);
            self::fail();
        } catch (BexPaymentException $e) {
            self::assertThat($e->getMessage(), self::equalTo('Enviroment can not be NULL or Empty.'));
        }
    }

    public function testNotStartPaymentAndThrowExceptionEmptyMerchantId()
    {
        try {
            BexPayment::startBexPayment(self::enviroment, null, PRIVATE_KEY);
            self::fail();
        } catch (BexPaymentException $e) {
            self::assertThat($e->getMessage(), self::equalTo('Merchant id can not be NULL or Empty.'));
        }
    }

    public function testNotStartPaymentAndThrowExceptionEmptyMerchantPrivateKey()
    {
        try {
            BexPayment::startBexPayment(self::enviroment, MERCHANT_ID, null);
            self::fail();
        } catch (BexPaymentException $e) {
            self::assertThat($e->getMessage(), self::equalTo('Merchant Private Key can not be NULL or Empty.'));
        }
    }
}
