<?php

namespace Omnipay\Ameria\Message;

class CancelRequest extends AbstractRequest
{
    public function getData() : array
    {
        $this->validate('paymentId');

        $data = parent::getData();

        $data['PaymentID'] = $this->getPaymentId();

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint() : string
    {
        return $this->getUrl() . '/CancelPayment';
    }
}
