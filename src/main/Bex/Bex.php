<?php

use Bex\exceptions\BexException;

require_once __DIR__.DIRECTORY_SEPARATOR.'autoload.php';

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    throw new BexException('PHP version >= 5.4.0 required');
}
function requireDependencies()
{
    $requiredExtensions = ['openssl'];
    foreach ($requiredExtensions as $ext) {
        if (!extension_loaded($ext)) {
            throw new BexException('The Bex library requires the '.$ext.' extension.');
        }
    }
}

requireDependencies();
