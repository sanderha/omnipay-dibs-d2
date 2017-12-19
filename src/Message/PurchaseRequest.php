<?php

namespace Omnipay\DibsD2\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Dummy Authorize Request
 */
class PurchaseRequest extends AbstractRequest
{
    public $endpoint = 'https://payment.architrade.com/paymentweb/start.action';


    public function getData()
    {
        $data = [
            'accepturl' => $this->getParameter('accepturl'),
            'amount' => $this->getParameter('amount'),
            'callbackurl' => $this->getParameter('callbackurl'),
            'currency' => $this->getParameter('currency'),
            'merchant' => $this->getParameter('merchant'),
            'orderid' => $this->getParameter('orderid'),
            'billingAddress' => $this->getParameter('billingAddress'),
            'billingAddress2' => $this->getParameter('billingAddress2'),
            'billingFirstName' => $this->getParameter('billingFirstName'),
            'billingLastName' => $this->getParameter('billingLastName'),
            'billingPostalCode' => $this->getParameter('billingPostalCode'),
            'billingPostalPlace' => $this->getParameter('billingPostalPlace'),
            'cardholder_name' => $this->getParameter('cardholder_name'),
            'cardholder_address1' => $this->getParameter('cardholder_address1'),
            'cardholder_zipcode' => $this->getParameter('cardholder_zipcode'),
            'email' => $this->getParameter('email'),
            'md5key' => $this->getParameter('md5key'),
            'acquirerinfo' => $this->getParameter('acquirerinfo'),
            'account' => $this->getParameter('account'),
            'acquirerlang' => $this->getParameter('acquirerlang'),
            'calcfee' => $this->getParameter('calcfee'),
            'cancelurl' => $this->getParameter('cancelurl'),
            'capturenow' => $this->getParameter('capturenow'),
            'decorator' => $this->getParameter('decorator'),
            'HTTP_COOKIE' => $this->getParameter('http_cookie'),
            'ip' => $this->getParameter('ip'),
            'lang' => $this->getParameter('lang'),
            'maketicket' => $this->getParameter('maketicket'),
            'notifyurl' => $this->getParameter('notifyurl'),
            'ordertext' => $this->getParameter('ordertext'),
            'paytype' => $this->getParameter('paytype'),
            'postype' => $this->getParameter('postype'),
            'preauth' => $this->getParameter('preauth'),
            'return_checksum' => $this->getParameter('return_checksum'),
            'test' => $this->getTestMode() ? 'true' : null,
            'ticketrule' => $this->getParameter('ticketrule'),
            'uniqueoid' => $this->getParameter('uniqueoid'),
            'voucher' => $this->getParameter('voucher'),
        ];

        if (is_array($this->getParameter('deliveries'))) {
            foreach ($this->getParameter('deliveries') as $key => $delivery) {
                foreach ($delivery as $dkey => $value) {
                    $data['delivery' . ($key + 1) . '.' . $dkey] = $value;
                }
            }
        }

        return array_filter($data);
    }

    public function sendData($data)
    {
        return new PurchaseResponse($this, $data, $this->endpoint);
    }

    // Getters and setters
    public function __call($method, $params) {
        if (substr($method,0,3) == 'get') {
            return $this->getParameter(lcfirst(substr($method,3)));
        } else if (substr($method,0,3) == 'set') {
            $this->setParameter(lcfirst(substr($method,3)), $params[0]);
        } else {
            return null;
        }
    }
}
