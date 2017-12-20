<?php

namespace Omnipay\DibsD2\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Dummy Response
 */
class CompleteResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return true;
    }

    public function getTransactionReference()
    {
        return isset($this->data['transact']) ? $this->data['transact'] : null;
    }
}
