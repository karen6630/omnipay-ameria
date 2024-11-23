<?php

namespace Omnipay\Ameria\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Ameria Response.
 *
 * This is the response class for all Ameria requests.
 *
 * @see \Omnipay\Ameria\Gateway
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    const PAYMENT_STARTED = '0';
    const PAYMENT_APPROVED = '1';
    const PAYMENT_DEPOSITED = '2';
    const PAYMENT_VOID = '3';
    const PAYMENT_REFUNDED = '4';
    const PAYMENT_AUTOAUTHORIZED = '5';
    const PAYMENT_DECLINED = '6';

    const PAYMENT_COMPLETED = '00';

    /**
     * Live gateway merchant URL.
     *
     * @var string URL
     */
    protected $merchantEndpoint = 'https://services.ameriabank.am/VPOS/Payments/Pay';

    /**
     * Test gateway merchant URL.
     *
     * @var string
     */
    protected $merchantTestEndpoint = 'https://servicestest.ameriabank.am/VPOS/Payments/Pay';

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        parent::__construct($request, $data);

        $this->request = $request;
        $this->data = json_decode($data, true);
        $this->headers = $headers;
    }

    /**
     * Is the transaction successful
     *
     * @return bool
     */
    public function isSuccessful() : bool
    {
        if ($this->getOrderStatus()) {
            return $this->isDeposited() && $this->isCompleted();
        }

        return $this->isCompleted() ?: $this->isNotError();
    }

    /**
     * @return bool
     */
    public function isCompleted() : bool
    {
        return $this->getCode() === self::PAYMENT_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isRedirect() : bool
    {
        return ($this->isNotError() && !$this->getBindingID());
    }

    /**
     * Is the response no error
     *
     * @return bool
     */
    public function isNotError()
    {
        return $this->getCode() === self::PAYMENT_APPROVED;
    }

    /**
     * Authorization via ACS of the issuer bank
     *
     * @return bool
     */
    public function isAuthorized()
    {
        return $this->getOrderStatus() === self::PAYMENT_AUTOAUTHORIZED;
    }

    /**
     * Authorization cancelled
     *
     * @return bool
     */
    public function isVoid()
    {
        return $this->getOrderStatus() === self::PAYMENT_VOID;
    }

    /**
     * Amount successfully authorized
     *
     * @return bool
     */
    public function isDeposited()
    {
        return $this->getOrderStatus() === self::PAYMENT_DEPOSITED;
    }

    /**
     * Amount of the transaction was refunded
     *
     * @return bool
     */
    public function isRefunded()
    {
        return $this->getOrderStatus() === self::PAYMENT_REFUNDED;
    }

    /**
     * Order is registered but not paid
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->getOrderStatus() === self::PAYMENT_STARTED;
    }

    /**
     * Authorization declined
     *
     * @return bool
     */
    public function isDeclined()
    {
        return $this->getOrderStatus() === self::PAYMENT_DECLINED;
    }

    /**
     * Get response redirect url
     *
     * @return string
     */
    public function getRedirectUrl() : string
    {
        return $this->getMerchantUrl().'?'.http_build_query($this->getRedirectData());
    }

    /**
     * @return string
     */
    public function getMerchantUrl()
    {
        return $this->getRequest()->getTestMode() ? $this->merchantTestEndpoint : $this->merchantEndpoint;
    }

    /**
     * @return array
     */
    public function getRedirectData()
    {
        return [
            'id'   => $this->getPaymentId(),
            'lang' => $this->getRequest()->getLanguage(),
        ];
    }

    /**
     * Get the orderStatus.
     *
     * @return integer|null
     */
    public function getOrderStatus()
    {
        if (isset($this->data['OrderStatus'])) {
            return $this->data['OrderStatus'];
        }

        return null;
    }

    /**
     * Get the PaymentID reference.
     *
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->data['PaymentID'] ?? $this->request->getPaymentId() ?? null;
    }

    /**
     * Get the error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getMessage() : ?string
    {
        if (isset($this->data['ResponseMessage'])) {
            return $this->data['ResponseMessage'];
        }

        return null;
    }

    /**
     * Get the error code from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getCode() : ?string
    {
        if (isset($this->data['ResponseCode'])) {
            return $this->data['ResponseCode'];
        }

        return null;
    }

    /**
     * Get the BindingID reference.
     *
     * @return mixed
     */
    public function getBindingID()
    {
        if (isset($this->data['BindingID'])) {
            return $this->data['BindingID'];
        }

        return null;
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        return $this->getPaymentId();
    }
}
