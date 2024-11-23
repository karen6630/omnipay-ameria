<?php

namespace Omnipay\Ameria;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Ameria Gateway
 *
 * @method RequestInterface authorize(array $options = array())
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface capture(array $options = array())
 * @method RequestInterface completePurchase(array $options = array())
 * @method RequestInterface void(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 * @method RequestInterface fetchTransaction(array $options = [])
 * @method NotificationInterface acceptNotification(array $options = array())
 */
class Gateway extends AbstractGateway
{
    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Ameria';
    }


    /**
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return [
            'clientId' => '',
            'username' => '',
            'password' => '',
        ];
    }

    /**
     * Get account client id.
     *
     * @return mixed
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    /**
     * Set account client id.
     *
     * @param $value
     * @return $this
     */
    public function setClientId($value): Gateway
    {
        return $this->setParameter('clientId', $value);
    }

    /**
     * Get account login.
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set account login.
     *
     * @param $value
     * @return $this
     */
    public function setUsername($value): Gateway
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get account password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set account password.
     *
     * @param $value
     * @return $this
     */
    public function setPassword($value): Gateway
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Create Purchase Request.
     *
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $options = array()): \Omnipay\Common\Message\AbstractRequest
    {
        return $this->createRequest('\Omnipay\Ameria\Message\RegisterRequest', $options);
    }

    /**
     * Create GetOrderStatus Request.
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getOrderStatus(array $parameters = array()): \Omnipay\Common\Message\AbstractRequest
    {
        return $this->createRequest('\Omnipay\Ameria\Message\GetOrderStatusRequest', $parameters);
    }

    /**
     * Create Refund Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function refund(array $parameters = array()): \Omnipay\Common\Message\AbstractRequest
    {
        return $this->createRequest('\Omnipay\Ameria\Message\RefundRequest', $parameters);
    }

    public function cancel(array $parameters = array()): \Omnipay\Common\Message\AbstractRequest
    {
        return $this->createRequest('\Omnipay\Ameria\Message\CancelRequest', $parameters);
    }


}
