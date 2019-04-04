<?php

namespace Bex\merchant\request;

use Bex\exceptions\BexException;
use Bex\merchant\security\EncryptionUtil;

class TicketRequest
{
    private $type;
    private $installmentUrl;
    private $nonceUrl;
    private $amount;
    private $orderId;
    private $tckn;
    private $msisdn;
    private $campaignCode;
    private $agreementUrl;
    private $address;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getInstallmentUrl()
    {
        return $this->installmentUrl;
    }

    /**
     * @param mixed $installmentUrl
     */
    public function setInstallmentUrl($installmentUrl)
    {
        $this->installmentUrl = $installmentUrl;
    }

    /**
     * @return mixed
     */
    public function getNonceUrl()
    {
        return $this->nonceUrl;
    }

    /**
     * @param mixed $nonceUrl
     */
    public function setNonceUrl($nonceUrl)
    {
        $this->nonceUrl = $nonceUrl;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getTckn()
    {
        return $this->tckn;
    }

    /**
     * @param array $tckn
     */
    public function setTckn(array $tckn)
    {
        if (null == $tckn) {
            return $this;
        }

        if (11 != strlen($tckn['no'])) {
            throw new BexException('TCKN Must be 11 numbers long');
        } else {
            $encryptedTcknNo = EncryptionUtil::encryptBex($tckn['no']);
            $tcknCheck = $tckn['check'];
            if (null == $tcknCheck) {
                $tcknCheck = false;
            }
            $tcknResult['check'] = $tcknCheck;
            $tcknResult['no'] = $encryptedTcknNo;
            $this->tckn = $tcknResult;
        }
    }

    /**
     * @return mixed
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * @param mixed $msisdn
     */
    public function setMsisdn($msisdn)
    {
        if (null == $msisdn) {
            return $this;
        }
        if (10 != strlen($msisdn['no'])) {
            throw new BexException('MSISDN must be 10 numbers without a leading zero.');
        } else {
            $encryptedMsisdnNo = EncryptionUtil::encryptBex($msisdn['no']);
            $msisdnCheck = $msisdn['check'];
            if (null == $msisdnCheck) {
                $msisdnCheck = false;
            }
            $msisdnResult['check'] = $msisdnCheck;
            $msisdnResult['no'] = $encryptedMsisdnNo;
            $this->msisdn = $msisdnResult;
        }
    }

    /**
     * @return mixed
     */
    public function getCampaignCode()
    {
        return $this->campaignCode;
    }

    /**
     * @param mixed $campaignCode
     */
    public function setCampaignCode($campaignCode)
    {
        $this->campaignCode = $campaignCode;
    }

    public function getAgreementUrl()
    {
        return $this->agreementUrl;
    }

    public function setAgreementUrl($agreementUrl)
    {
        $this->agreementUrl = $agreementUrl;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        if (null == $address) {
            $this->address = false;
        }
        $this->address = $address;
    }
}
