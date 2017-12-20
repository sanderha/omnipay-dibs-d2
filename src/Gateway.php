<?php

namespace Omnipay\DibsD2;

use Omnipay\Common\AbstractGateway;
use Omnipay\DibsD2\Message\CaptureRequest;
use Omnipay\DibsD2\Message\PurchaseRequest;
use Omnipay\DibsD2\Message\CompleteRequest;
use Omnipay\DibsD2\Message\AuthorizeRequest;
use Omnipay\DibsD2\Message\RefundRequest;
use Omnipay\DibsD2\Message\VoidRequest;

/**
 * DibsD2 Gateway
 *
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'DibsD2';
    }

    /**
     * Get default parameters
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'key1' => '',
            'key2' => '',
            'merchantId' => '',
            'testMode' => false,
        );
    }

    /**
     * @param array $parameters
     * @return PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return CompleteRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompleteRequest::class, $parameters);
    }

    /**
     * @param array $options
     * @return AuthorizeRequest
     */
    public function authorize(array $options = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    /**
     * @param array $options
     * @return CompleteRequest
     */
    public function completeAuthorize(array $options = array())
    {
        return $this->createRequest(CompleteRequest::class, $options);
    }

    /**
     * @param array $options
     * @return CaptureRequest
     */
    public function capture(array $options = array())
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    /**
     * @param array $options
     * @return VoidRequest
     */
    public function void(array $options = array())
    {
        return $this->createRequest(VoidRequest::class, $options);
    }

    /**
     * @param array $options
     * @return RefundRequest
     */
    public function refund(array $options = array())
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    // Gateway getters and setters
    public function setKey1($value)
    {
        return $this->setParameter('key1', $value);
    }

    public function getKey1()
    {
        return $this->getParameter('key1');
    }

    public function setKey2($value)
    {
        return $this->setParameter('key2', $value);
    }

    public function getKey2()
    {
        return $this->getParameter('key2');
    }

    public function setLang($value)
    {
        return $this->setParameter('lang', strtolower($value));
    }

    public function getLang()
    {
        return $this->getParameter('lang');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }
}
