<?php

namespace Omnipay\DibsD2\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Dummy Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return true;
    }

    public function getTransactionReference()
    {
        return isset($this->data['orderid']) ? $this->data['orderid'] : null;
    }
}
