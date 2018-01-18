<?php

namespace Omnipay\DibsD2\Message;

class CaptureRequest extends GeneralRequest
{
    public $endpoint = 'https://payment.architrade.com/cgi-bin/capture.cgi';

    public function getData()
    {
        $data = [
            'merchant'      => $this->getMerchantId(),
            'amount'        => $this->getAmountInteger(),
            'transact'      => $this->getTransactionId(),
            'orderid'       => $this->getOrderId(),
        ];

        return $data;
    }

    public function sendData($data)
    {
        $http_response = $this->httpClient->send('POST', $this->endpoint, [], $data);
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }
}
