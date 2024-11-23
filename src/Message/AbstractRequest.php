<?php

namespace Omnipay\Ameria\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use \Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;

/**
 * Class AbstractRequest
 * @package Omnipay\Ameria\Message
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    /**
     * Live Endpoint URL.
     *
     * @var string URL
     */
    protected $endpoint = 'https://services.ameriabank.am/VPOS/api/VPOS';

    /**
     * Test Endpoint URL.
     *
     * @var string
     */
    protected $testEndpoint = 'https://servicestest.ameriabank.am/VPOS/api/VPOS';

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
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    /**
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
    public function setUsername($value): AbstractRequest
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Set account password.
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
    public function setPassword($value): AbstractRequest
    {
        return $this->setParameter('password', $value);
    }

    abstract public function getEndpoint();

    /**
     * Get url. Depends on  test mode.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->endpoint;
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Set the request language.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLanguage($value): AbstractRequest
    {
        return $this->setParameter('language', $value);
    }

    /**
     * Get the request binding purchase.
     * @return bool
     */
    public function getBindingPurchase()
    {
        return $this->getParameter('bindingPurchase');
    }

    /**
     * Set the request binding purchase.
     *
     * @param $value
     *
     * @return $this
     */
    public function setBindingPurchase($value)
    {
        return $this->setParameter('bindingPurchase', $value);
    }

    /**
     * Get the card holder id.
     * @return mixed
     */
    public function getCardHolderId()
    {
        return $this->getParameter('cardHolderID');
    }

    /**
     * Set the card holder id.
     *
     * @param $value
     *
     * @return $this
     */
    public function setCardHolderId($value)
    {
        return $this->setParameter('cardHolderID', $value);
    }

    /**
     * Get the request Opaque(Additional data).
     * @return mixed
     */
    public function getOpaque()
    {
        return $this->getParameter('opaque');
    }

    /**
     * Set the request Opaque(Additional data).
     *
     * @param string $value
     *
     * @return $this
     */
    public function setOpaque($value)
    {
        return $this->setParameter('opaque', $value);
    }

    /**
     * Get the request paymentId.
     * @return $this
     */
    public function getPaymentId()
    {
        return $this->getParameter('paymentId');
    }

    /**
     * Set the request paymentId.
     *
     * @param $value
     *
     * @return $this
     */
    public function setPaymentId($value)
    {
        return $this->setParameter('paymentId', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $body = $data ? http_build_query($data, '', '&') : null;

        $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $body);

        return $this->createResponse($httpResponse->getBody()->getContents(), $httpResponse->getHeaders());
    }

    /**
     * @param $data
     * @param array $headers
     * @return Response
     */
    protected function createResponse($data, $headers = []): Response
    {
        return $this->response = new Response($this, $data, $headers);
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('username', 'password');

        return [
            'Username' => $this->getUsername(),
            'Password' => $this->getPassword(),
        ];
    }
}
