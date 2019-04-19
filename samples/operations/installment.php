<?php

require_once './setup.php';
require_once './BexUtil.php';

$userData = BexUtil::readJsonFile('./data.json');

$bex->installments(function ($installmentArray) {
    $installments = [];

    $totalAmountStr = $installmentArray['totalAmount'];

    $posConfig = BexUtil::vposConfig('vpos.json', $installmentArray['bank']);

    // iki taksit yapalım
    $installmentAmount = BexUtil::formatTurkishLira(
        BexUtil::toFloat($totalAmountStr) / 1.0
    );
    // 1.ci taksiti ayarlayalım.
    $installments[] = [
        'numberOfInstallment' => 1,
        'installmentAmount' => $installmentAmount,
        'totalAmount' => $totalAmountStr,
        'vposConfig' => $posConfig,
    ];

    // iki taksit yapalım
    $installmentAmount = BexUtil::formatTurkishLira(
        BexUtil::toFloat($totalAmountStr) / 2.0
    );

    // 2. Taksiti ayarlayalım.
    $installments[] = [
        'numberOfInstallment' => 2,
        'installmentAmount' => $installmentAmount,
        'totalAmount' => $totalAmountStr,
        'vposConfig' => $posConfig,
    ];
    // Taksitleri bir array olarak dönelim.
    return $installments;
});
