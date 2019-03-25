<?php

namespace Bex\util;

use Bex\exceptions\MoneyUtilException;

class MoneyUtils
{
    //MONEY_FORMAT = "####,##";

    public static function formatTurkishLira($amount)
    {
        return self::format($amount);
    }

    private static function format($amount)
    {
        $formattedAmount = str_replace(',', '.', $amount);
        $formattedAmount = sprintf('%01.2f', $formattedAmount);
        $formattedAmount = str_replace('.', ',', $formattedAmount);

        return $formattedAmount;
    }

    public static function toFloat($amount)
    {
        $formattedAmount = str_replace(',', '.', $amount);

        return $formattedAmount;
    }

    public static function toDouble($amount)
    {
        return self::toFloat($amount);
    }

    //TODO refactor
    public static function enforceAmountFormat($amount)
    {
        if (strpos($amount, '.')) {
            throw new MoneyUtilException('Input validation error.');
        }
        $splitted = explode(',', $amount);
        if (2 != count($splitted) || 2 != strlen($splitted[1])) {
            throw new MoneyUtilException('Input validation error.');
        }

        return $amount;
    }
}
