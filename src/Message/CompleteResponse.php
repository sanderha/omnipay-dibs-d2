<?php

namespace Omnipay\DibsD2\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Dummy Response
 */
class CompleteResponse extends AbstractResponse
{
    private $statusCodes = [
        0 => 'transaction inserted',
        1 => 'declined',
        2 => 'authorization approved',
        3 => 'capture sent to acquirer',
        4 => 'capture declined by acquirer',
        5 => 'capture completed',
        6 => 'authorization deleted',
        7 => 'capture balanced',
        8 => 'partially refunded and balanced',
        9 => 'refund sent to acquirer',
        10 => 'refund declined',
        11 => 'refund completed',
        12 => 'capture pending',
        13 => '"ticket" transaction',
        14 => 'deleted "ticket" transaction',
        15 => 'refund pending',
        16 => 'waiting for shop approval',
        17 => 'declined by DIBS',
        18 => 'multicap transaction open',
        19 => 'multicap transaction closed',
        26 => 'postponed',
    ];


    public function isSuccessful()
    {
        return in_array($this->data['statuscode'], [2,5,7,11]);
    }

    public function isCaptured()
    {
        return $this->data['statuscode'] === 5;
    }

    public function isAuthorized()
    {
        return $this->data['statuscode'] === 2;
    }

    public function getTransactionReference()
    {
        return isset($this->data['transact']) ? $this->data['transact'] : null;
    }

    public function getStatus()
    {
        return isset($this->data['statuscode']) ? $this->statusCodes[$this->data['statuscode']] : null;
    }

    public function getOrderId()
    {
        return isset($this->data['orderid']) ? $this->data['orderid'] : null;
    }
}
