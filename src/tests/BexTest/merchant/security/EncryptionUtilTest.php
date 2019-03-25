<?php

namespace test\BexTest\merchant\security;

require_once dirname(dirname(__DIR__)).'/Setup.php';

use Bex\merchant\security;
use Bex\merchant\service\MerchantService;
use BexTest\Setup;

class EncryptionUtilTest extends Setup
{
    const INSTALLMENT_URL = 'https://test-api.bkmexpress.com.tr/merchant/installments';

    const MERCHANT_BASE64ENCODED_SIGNATURE = 'IGNoNOR/qq+GSQBJBsBuaTgNEWl253Toq+YRrrvoAUvIBy4ylUpglgOOaj/qXR0StGXwvRkEAo2MQCXd+U7uGZ7vEo+a6pWzHcavRDHH2j9x67lxFESFnexI5M4soP6mJzMsyLtw2kq5ZSWhz2G4f/KUye2dR4P8ut6WmZ4qbqE=';
    const INSTALLMENT_BASE64ENCODED_SIGNATURE = 'DOq+6ZGNjB5Wcq5ETi2/+77/9LGXpEBsDh6Y4tr5mC9y1hPKWFWBU55Jj1vwnlBWLIN6oI0wEsYymVPEZQkeEcxRRkBvAE8WV25x/te+1HG7k6CXpRONqp8XyTRt4Sxj+MPPY4S4HED8yRnA4XEyNB4fDBgVpMfVWKzTMZQHGYQ4xcAtqCbCe7HIGtk8kKMK0bTB0WyrazFvTqrD0+Oca73fxaQvYOOwSmjXrUz8PUxO6VpaVH/ZlW2R8ay+/Q4i4QVSyp64HmimI9iB9s+GCSlZfkRV8iq0kdNgidDi9faqdH3eRHbudaupnqYjfc6yb8MlMx8qUmbJrUpWpTIWgw==';

    /*public function testSignShouldPassWhenParametersAreCorrect()
    {
        $configuration = Setup::integrationMerchantConfig();
        $privateKey = $configuration->getMerchantPrivateKey();
        $data = $configuration->getMerchantId();

        $result = security\EncryptionUtil::sign($data, $privateKey);

        self::assertEquals($result, self::MERCHANT_BASE64ENCODED_SIGNATURE);
    }

    public function testVerifyShouldPassWhenParametersAreCorrect()
    {
        $config = Setup::integrationMerchantConfig();
        $merchantService = new MerchantService($config);
        $merchantResponse = $merchantService->login();
        $data = security\EncryptionUtil::verifyBexSign('1234', self::INSTALLMENT_BASE64ENCODED_SIGNATURE);
        self::assertEquals($data, true);
    }*/

    public function testEncryptWithBex()
    {
        $data = security\EncryptionUtil::encryptWithBex('1234');
        $this->assertInternalType('string', gettype($data));
    }
}
