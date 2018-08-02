<?php

namespace Omnipay\DibsD2\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Dummy Response
 */
class CompleteResponse extends AbstractResponse
{
    private $statusCodes = [
        0  => 'transaction inserted',
        1  => 'declined',
        2  => 'authorization approved',
        3  => 'capture sent to acquirer',
        4  => 'capture declined by acquirer',
        5  => 'capture completed',
        6  => 'authorization deleted',
        7  => 'capture balanced',
        8  => 'partially refunded and balanced',
        9  => 'refund sent to acquirer',
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

    private $threeDstatusCodes = [
        0 => 'success - The cardholder completed authentication correctly.',
        1 => 'attempt - The cardholder is not participating, but the attempt to authenticate was recorded.',
        2 => 'failure - The cardholder did not complete authentication',
        3 => 'error - A system error prevented authentication from completing',
        4 => 'not enrolled - The card was not enrolled for 3d secure'
    ];


    public function isSuccessful()
    {
        if ($this->getThreeDSecure() && $this->getThreeDSecure() === 0) {
            return true;
        }

        return in_array($this->data['statuscode'], [2, 5, 7, 11]);
    }

    public function isPending()
    {
        if($this->getThreeDSecure() && $this->getThreeDSecure() === 1){
            return true;
        }

        return in_array($this->data['statuscode'], [12, 15, 16]);
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
        $msg = isset($this->data['statuscode']) ? $this->statusCodes[$this->data['statuscode']] : null;

        if ($this->getThreeDSecure() && $this->getThreeDSecure() !== 4) {
            $msg = $this->threeDstatusCodes[$this->getThreeDSecure()];
        }

        return $msg;
    }

    public function getTransactionId()
    {
        return isset($this->data['orderid']) ? $this->data['orderid'] : null;
    }

    /**
     * @return bool
     */
    public function getThreeDSecure()
    {
        return isset($this->data['threeDstatus']) ? $this->data['threeDstatus'] : null;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getStatus();
    }
}
