<?php

namespace Bex\merchant\request;

use Bex\util\MoneyUtils;

class Builder extends TicketRequest
{
    private $ticketRequest;

    public function __construct($type)
    {
        parent::setType($type);
    }

    public static function newPayment($type)
    {
        return new Builder($type);
    }

    public function amountAndInstallmentUrl(Builder $builder)
    {
        $this->amount(MoneyUtils::enforceAmountFormat($builder->getAmount()));
        $this->installmentUrl($builder->getInstallmentUrl());
        $this->nonceUrl($builder->getNonceUrl());
        $this->campaignCode($builder->getCampaignCode());
        $this->orderId($builder->getOrderId());
        if (null != $builder->getTckn()) {
            $this->tckn($builder->getTckn()['no'], $builder->getTckn()['check']);
        }

        if (null != $builder->getMsisdn()) {
            $this->msisdn($builder->getMsisdn()['no'], $builder->getMsisdn()['check']);
        }
        $this->agreementUrl($builder->getAgreementUrl());
        $this->address($builder->getAddress());

        return $this;
    }

    public function amount($amount)
    {
        if (null == $amount) {
            return $this;
        }
        parent::setAmount($amount);

        return $this;
    }

    public function installmentUrl($installmentUrl)
    {
        if (null == $installmentUrl) {
            return $this;
        }
        parent::setInstallmentUrl($installmentUrl);

        return $this;
    }

    public function nonceUrl($nonceUrl)
    {
        if (null == $nonceUrl) {
            return $this;
        }
        parent::setNonceUrl($nonceUrl);

        return $this;
    }

    public function campaignCode($campaignCode)
    {
        if (null == $campaignCode) {
            return $this;
        }
        parent::setCampaignCode($campaignCode);

        return $this;
    }

    public function orderId($orderId)
    {
        if (null == $orderId) {
            return $this;
        }
        parent::setOrderId($orderId);

        return $this;
    }

    public function tckn($number, $check)
    {
        if (null == $number) {
            return $this;
        }
        parent::setTckn(array('no' => $number, 'check' => $check));

        return $this;
    }

    public function msisdn($phoneNumber, $check)
    {
        if (null == $phoneNumber) {
            return $this;
        }
        parent::setMsisdn(array(
            'no' => $phoneNumber,
            'check' => $check,
        ));

        return $this;
    }

    public function agreementUrl($agreementUrl)
    {
        if (null == $agreementUrl) {
            return $this;
        }
        parent::setAgreementUrl($agreementUrl);

        return $this;
    }

    public function address($address)
    {
        if (null == $address) {
            return $this;
        }
        parent::setAddress($address);

        return $this;
    }

    public function build()
    {
        return $this->ticketRequest;
    }
}
