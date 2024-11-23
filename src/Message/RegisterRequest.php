<?php

namespace Omnipay\Ameria\Message;


/**
 * Class RegisterRequest
 * @package Omnipay\Ameria\Message
 */
class RegisterRequest extends AbstractRequest
{
    const PAYMENT_TYPE_ARCA = 5;
    const PAYMENT_TYPE_PAYPAL = 7;
    const PAYMENT_TYPE_BINDING = 6;

    /**
     * Prepare data to send
     *
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionId', 'amount', 'returnUrl');

        $data = parent::getData();
        $data['ClientID'] = $this->getClientId();
        $data['OrderID'] = $this->getTransactionId();
        $data['Amount'] = $this->getAmount();
        $data['BackURL'] = $this->getReturnUrl();

        if ($this->getCurrency()) {
            $data['Currency'] = str_pad($this->getCurrencyNumeric(), 3, 0, STR_PAD_LEFT);
        }

        if ($this->getDescription()) {
            $data['Description'] = $this->getDescription();
        }

        if ($this->getLanguage()) {
            $data['language'] = $this->getLanguage();
        }

        if ($this->getCardHolderId()) {
            $data['CardHolderID'] = $this->getCardHolderId();
        }

        if ($this->getOpaque()) {
            $data['Opaque'] = $this->getOpaque();
        }

        if ($this->getBindingPurchase()) {
            $data['PaymentType'] = self::PAYMENT_TYPE_BINDING;
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getUrl() . ($this->getBindingPurchase() ? '/MakeBindingPayment' : '/InitPayment');
    }
}