<?php

namespace test\BexTest\merchant\service;

require_once dirname(dirname(__DIR__)).'/Setup.php';

use Bex\config\Configuration;
use Bex\merchant\service\MerchantService;
use BexTest\Setup;

class MerchantServiceTest extends Setup
{
    const INSTALLMENT_URL = 'https://bex-demo.finartz.com/merchant/installments';

    public function testLoginWithParameters()
    {
        $this->markTestSkipped('Bex\exceptions\MerchantServiceException: Merchant login got connection problem.');
        $config = Setup::integrationMerchantConfig();
        $this->assertInstanceOf(Configuration::class, $config);
        $merchantService = new MerchantService($config);
        $this->assertInstanceOf(MerchantService::class, $merchantService);

        $merchantService->login();

        self::assertEquals('ok', $merchantService->login()->getResult());
    }

    public function testCreateOneTimeTicket()
    {
        $this->markTestSkipped('Bex\exceptions\MerchantServiceException: Merchant login got connection problem.');
        $config = Setup::integrationMerchantConfig();
        $merchantService = new MerchantService($config);
        $merchantResponse = $merchantService->login();
        $merchantService->oneTimeTicket($merchantResponse->getToken(), '1000,52', self::INSTALLMENT_URL);
        var_dump($merchantService->oneTimeTicket($merchantResponse->getToken(), '1000,52', self::INSTALLMENT_URL));
        self::assertEquals('ok', $merchantService->oneTimeTicket($merchantResponse->getToken(), '1000,52', self::INSTALLMENT_URL)->getResult());
    }

    /*
    public function testCallResult()
    {
        $config = Setup::integrationMerchantConfig();
        $merchantService = new MerchantService($config);
        $merchantResponse = $merchantService->login();
        $ticketResponse = $merchantService->oneTimeTicket($merchantResponse->getToken(), "1000,52", self::INSTALLMENT_URL);
        $ticketId = $ticketResponse->getTicketPath();
        $merchantConnectionId = $merchantResponse->getPath();
        $merchantToken = $merchantResponse->getConnectionToken();
        $resultResponse = $merchantService->result($merchantToken, $merchantConnectionId, $ticketId);


        self::assertEquals("ok", $resultResponse->getStatus());

    }*/
}
