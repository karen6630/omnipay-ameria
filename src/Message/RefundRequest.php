<?php

namespace Omnipay\Ameria\Message;

/**
 * Class RefundRequest
 * @package Omnipay\Ameria\Message
 */
class RefundRequest extends AbstractRequest
{
    public function getData() : array
    {
        $this->validate('paymentId', 'amount');

        $data = parent::getData();

        $data['PaymentID'] = $this->getPaymentId();
        $data['Amount'] = $this->getAmount();

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint() : string
    {
        return $this->getUrl() . '/RefundPayment';
    }
}
