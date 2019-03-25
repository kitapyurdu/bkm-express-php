<?php

require_once "../../src/main/Bex/Bex.php";

use Bex\merchant\security\EncryptionUtil;
use Bex\util\MoneyUtils;

class BexUtil
{

    public static function readJsonFile($jsonFile)
    {
        return json_decode(file_get_contents($jsonFile), true);
    }

    public static function writeJsonFile($jsonFile, $jsonData)
    {
        return file_put_contents($jsonFile, utf8_encode(json_encode($jsonData, JSON_PRETTY_PRINT)));
    }

    public static function formatTurkishLira($amount)
    {
        return MoneyUtils::formatTurkishLira($amount);
    }

    public static function toFloat($amount)
    {
        return MoneyUtils::toFloat($amount);
    }

    public static function vposConfig($vposJsonFile, $bank)
    {
        $vposConfigs = json_decode(file_get_contents($vposJsonFile), true);
        $vposConfig = $vposConfigs[$bank];
        if ($vposConfig == null) {
            throw new Exception("$bank not found in $vposJsonFile vpos configuration file");
        }
        return EncryptionUtil::encryptWithBex(json_encode($vposConfig));
    }
}

$util = new BexUtil($configurations);

?>