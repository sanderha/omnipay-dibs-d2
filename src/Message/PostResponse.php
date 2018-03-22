<?php
/**
 * Created by PhpStorm.
 * User: jkh
 * Date: 12/19/17
 * Time: 3:34 PM
 */

namespace Omnipay\DibsD2\Message;

use Omnipay\Common\Message\AbstractResponse;


class PostResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return $this->data['status'] === 'ACCEPTED';
    }

    public function getTransactionReference()
    {
        return isset($this->data['transact']) ? $this->data['transact'] : null;
    }

    public function getError()
    {
        switch ($this->data['reason']) {
            case 1 :
                return 'No response from acquirer';
                break;
            case 2 :
                return 'Timeout';
                break;
            case 3 :
                return 'Credit card expired';
                break;
            case 4 :
                return 'Rejected by acquirer';
                break;
            case 5 :
                return 'Authorisation older than 7 days';
                break;
            case 6 :
                return 'Transaction status on the DIBS server does not allow function';
                break;
            case 7 :
                return 'Amount too high';
                break;
            case 8 :
                return 'Error in the parameters sent to the DIBS server. An additional parameter called "message" is returned, with a value that may help identifying the error';
                break;
            case 9 :
                return 'Order number (orderid) does not correspond to the authorisation order number';
                break;
            case 10 :
                return 'Re-authorisation of the transaction was rejected';
                break;
            case 11 :
                return 'Not able to communicate with the acquier';
                break;
            case 12 :
                return 'Confirm request error';
                break;
            case 14 :
                return 'Capture is called for a transaction which is pending for batch - i.e. capture was already called';
                break;
            case 15 :
                return 'Capture or refund was blocked by DIBS';
                break;
            default:
                return 'Unknown error';
        }
    }

    /**
     * @return string
     */
    public function getMessage(){
        if(isset($this->data['reason'])){
            return $this->getError();
        }
        return 'Status: ' . $this->data['status'];
    }
}
