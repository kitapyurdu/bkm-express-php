<?php

namespace test\BexTest\merchant\request;

require_once dirname(dirname(__DIR__)).'/Setup.php';

use Bex\config\BexPayment;
use BexTest\Setup;

class InstallmentRequestTest extends Setup
{
    public function test()
    {
        $this->markTestSkipped('Not a valid test case');
        self::assertEquals(Setup::integrationMerchantConfig(), new BexPayment('DEV', 'merchant_id', 'merchant_secret'));
    }
}
