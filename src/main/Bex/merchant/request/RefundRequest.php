<?php

namespace Bex\merchant\request;

class RefundRequest
{
    /**
     * doReversalWithNoRef
     * işleminin referans numarası.
     *
     * @var
     */
    public $uniqueReferans;

    /**
     * Firma merchant id’si.
     *
     * @var
     */
    public $merchantId;

    /**
     * İptal için 1; iade için 2.
     *
     * @var int
     */
    public $requestType;

    /**
     * İptal / iade edilecektutar bilgisi.
     * "0,00" formatında iletilir.
     *
     * @var string
     */
    public $amount;

    /**
     * İşlemin döviz kodu. 949 gibi.
     *
     * @var
     */
    public $currency;

    /**
     * POS’a giderken kullanıcı adıdır. kullanılacak.
     *
     * @var string
     */
    public $posUid;

    /**
     * POS’a giderken şifredir.
     *
     * @var string
     */
    public $posPwd;

    /**
     * BKM tarafından ödemesi yapılmış işleme ait token bilgisi yazılır.
     *
     * @var string
     */
    public $transactionToken;

    /**
     * İptal iade işlemlerinde banka bazında gerekli olabilecek parametrelerin iletileceği alan(JSON formatta).
     *
     * @var string
     */
    public $extra;

    /**
     * yyyyMMdd-hh:mm:ss formatında.
     *
     * @var string
     */
    public $ts;

    /**
     * Tüm alanların tablodaki sırayla
     * concat edilerek “SHA-256 with
     * RSA” ile imzalanmış hali ->
     * “Extra” parametresini imzaya
     * dahil etmeyiniz.
     *
     * uniqueReferans , merchantId , requestType , amount , currency , posUid , posPwd , transactionToken, ts
     *
     * @var string
     */
    public $s;

    /**
     * @return mixed
     */
    public function getUniqueReferans()
    {
        return $this->uniqueReferans;
    }

    /**
     * @param mixed $uniqueReferans
     *
     * @return RefundRequest
     */
    public function setUniqueReferans($uniqueReferans)
    {
        $this->uniqueReferans = $uniqueReferans;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param mixed $merchantId
     *
     * @return RefundRequest
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * @return int
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * @param int $requestType
     *
     * @return RefundRequest
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     *
     * @return RefundRequest
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     *
     * @return RefundRequest
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getPosUid()
    {
        return $this->posUid;
    }

    /**
     * @param string $posUid
     *
     * @return RefundRequest
     */
    public function setPosUid($posUid)
    {
        $this->posUid = $posUid;

        return $this;
    }

    /**
     * @return string
     */
    public function getPosPwd()
    {
        return $this->posPwd;
    }

    /**
     * @param string $posPwd
     *
     * @return RefundRequest
     */
    public function setPosPwd($posPwd)
    {
        $this->posPwd = $posPwd;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionToken()
    {
        return $this->transactionToken;
    }

    /**
     * @param string $transactionToken
     *
     * @return RefundRequest
     */
    public function setTransactionToken($transactionToken)
    {
        $this->transactionToken = $transactionToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param string $extra
     *
     * @return RefundRequest
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @return string
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * @param string $ts
     *
     * @return RefundRequest
     */
    public function setTs($ts)
    {
        $this->ts = $ts;

        return $this;
    }

    /**
     * @return string
     */
    public function getS()
    {
        return $this->s;
    }

    /**
     * @param string $s
     *
     * @return RefundRequest
     */
    public function setS($s)
    {
        $this->s = $s;

        return $this;
    }

    public function getSignString()
    {
        $string = $this->getUniqueReferans().$this->getMerchantId().$this->getRequestType().$this->getAmount().$this->getCurrency();

        if ($this->getPosUid()) {
            $string .= $this->getPosUid().$this->getPosPwd();
        }

        $string .= $this->getTransactionToken().$this->getTs();

        return $string;
    }
}
