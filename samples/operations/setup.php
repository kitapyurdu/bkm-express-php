<?php

// Reports all errors
// error_reporting(E_ALL);
// Do not display errors for the end-users (security issue)
// ini_set('display_errors', 'Off');
// Set a logging file
// ini_set('error_log','bex.log');

require_once __DIR__.'/../../vendor/autoload.php';
require_once './Bex.php';
require_once './BexUtil.php';
require_once  './config.php';

$serverUrl = "http://$_SERVER[HTTP_HOST]";
$bex = Bex::configure(
    ENVIRONMENT,
    MERCHANT_ID,
    PRIVATE_KEY
);

class Log {
    /** @var \Monolog\Logger */
    private static $logger;

    public static function debug($message, $context = [])
    {
        if(!self::$logger) {
            self::initLogger();
        }

        self::$logger->debug($message, $context);
    }

    private static function initLogger()
    {
        self::$logger = new \Monolog\Logger('bkm_samples');
        self::$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__.'/../samples.log'));
    }
}

