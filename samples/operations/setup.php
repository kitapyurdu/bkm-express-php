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

const ENVIRONMENT = \Bex\enums\Environment::PREPROD; // Environments: DEV, LOCAL, SANDBOX, PREPROD, PRODUCTION
const MERCHANT_ID = '';
const PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
-----END RSA PRIVATE KEY-----';

$serverUrl = "http://$_SERVER[HTTP_HOST]";
$bex = Bex::configure(
    ENVIRONMENT,
    MERCHANT_ID,
    PRIVATE_KEY
);
