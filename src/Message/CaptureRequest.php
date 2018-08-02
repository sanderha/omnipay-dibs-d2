<?php

namespace Omnipay\DibsD2\Message;

use Guzzle\Http\Message\RequestInterface;

class CaptureRequest extends GeneralRequest
{
    public $endpoint = 'https://payment.architrade.com/cgi-bin/capture.cgi';

    public function getData()
    {
        $data = [
            'merchant'      => $this->getMerchantId(),
            'amount'        => $this->getAmountInteger(),
            'transact'      => $this->getTransactionReference(),
            'orderid'       => $this->getTransactionId(),
        ];

        return $data;
    }

    public function sendData($data)
    {
        $http_response = $this->httpClient->createRequest(RequestInterface::POST, $this->endpoint, [], $data)->send();
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }
}
