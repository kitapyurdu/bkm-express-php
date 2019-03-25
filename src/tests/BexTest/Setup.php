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
    const merchantSecretKey = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAOLA7InQdCbT8n5Rx8zk8uSCFQ5q4Tyxl0Kr02DoykWxLMBUl1p0YU9hoiocv6Hako5rZssHG0Eb4prh2nmZNpyfhOoOw48Pzg3eB7hKjpXLEPKdK8oemonBcvJ+E9/at4KLg4epyGum1cGdiaYkVF8frG+z53b0ngEq7/CzU8htAgMBAAECgYBNn6OZzf1lKVsy+QX/00R/CzTwGZB/eYABd9bFrwtHbk6WjJ6/fWWuigq8hdjoLG3NSWEIEae30zbwtG5ZACUcNa00Ar9mjsQncZXvLXp9hNb6/TR/mKQvZTjXgoRgn/ltS48GSpqWKbmKVl5JQWgNTb1zHGs2igBb161/ag16tQJBAPzVo2YAVcqXCvuNEWhkqsW1+74GSCrX5QcQwv8qwpt7raumojoFCdeW+xt1Je/bsw01pywkvI3cIO0pdHKwDDcCQQDll7GOPUT/q3Gvmw+kCTnvEH/yYSR2XsPLfEvewxp7SbFI1orLO61A+r5uLDGcfPoxQ7AORzf/OpSfNTD7IGZ7AkAUs5Fbaq+blN5rVlOUjpmE8q+YEX+bMm4oM/EjX2brwCaqJUynH358znnk96SRjRWOAVScwq1FmD6B7KECOvPlAkEA4GaWlXbPFLFGKaP98o9N/5p547YMxGE1L5LqOO0q2euaCp4fBCrs2MD7FYW+a7w/cZ0924bCdYSVNNLxb9IoNwJAJ6PVEsZWT5uGTxqlbTBDFSjHF79OLFWllHsa+2uwf/f6OwNAAMagVbWSdAIlZtaiifDhhXkC4h3ozI1f3xolJg==';

    const enviroment = Environment::SANDBOX;

    const merchantId = '219be6b7-b3ca-4bd1-9886-a16d40b0bfe2';

    public function __construct()
    {
        self::integrationMerchantConfig();
    }

    /**
     * @return Configuration
     *
     * @throws BexPaymentException
     */
    public static function integrationMerchantConfig()
    {
        return BexPayment::startBexPayment(self::enviroment, self::merchantId, self::merchantSecretKey);
    }

    public function testStartPayment()
    {
        self::assertEquals(self::integrationMerchantConfig(), BexPayment::startBexPayment(self::enviroment, self::merchantId, self::merchantSecretKey));
    }

    public function testNotStartPaymentAndThrowExceptionUnknownEnviroment()
    {
        try {
            BexPayment::startBexPayment(null, self::merchantId, self::merchantSecretKey);
            self::fail();
        } catch (BexPaymentException $e) {
            self::assertThat($e->getMessage(), self::equalTo('Enviroment can not be NULL or Empty.'));
        }
    }

    public function testNotStartPaymentAndThrowExceptionEmptyMerchantId()
    {
        try {
            BexPayment::startBexPayment(self::enviroment, null, self::merchantSecretKey);
            self::fail();
        } catch (BexPaymentException $e) {
            self::assertThat($e->getMessage(), self::equalTo('Merchant id can not be NULL or Empty.'));
        }
    }

    public function testNotStartPaymentAndThrowExceptionEmptyMerchantPrivateKey()
    {
        try {
            BexPayment::startBexPayment(self::enviroment, self::merchantId, null);
            self::fail();
        } catch (BexPaymentException $e) {
            self::assertThat($e->getMessage(), self::equalTo('Merchant Private Key can not be NULL or Empty.'));
        }
    }
}
