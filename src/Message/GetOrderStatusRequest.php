<?php

namespace Omnipay\Ameria\Message;

use Omnipay\Ameria\Message\AbstractRequest;

/**
 * Class GetOrderStatusRequest
 * @package Omnipay\Ameria\Message
 */
class GetOrderStatusRequest extends AbstractRequest
{
    /**
     * Prepare data to send
     *
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData() : array
    {
        $this->validate('paymentId');

        $data = parent::getData();

        $data['PaymentID'] = $this->getPaymentId();

        if ($this->getLanguage()) {
            $data['language'] = $this->getLanguage();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint() : string
    {
        return $this->getUrl() . '/GetPaymentDetails';
    }
}