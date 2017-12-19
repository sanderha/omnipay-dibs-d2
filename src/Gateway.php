<?php

namespace Omnipay\DibsD2;

use Omnipay\Common\AbstractGateway;

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


    /**
     * @param array $parameters
     * @return \Omnipay\DibsD2\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\DibsD2\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\DibsD2\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\DibsD2\Message\CompletePurchaseRequest', $parameters);
    }
}
