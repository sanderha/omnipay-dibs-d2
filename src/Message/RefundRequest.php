<?php

namespace Omnipay\DibsD2\Message;

class RefundRequest extends GeneralRequest
{
    public $endpoint = 'https://%s:%s@payment.architrade.com/cgi-adm/refund.cgi';

    public function getData()
    {
        $data = [
            'merchant'      => $this->getMerchantId(),
            'transact'      => $this->getTransactionId(),
            'amount'        => $this->getAmountInteger(),
            'currency'      => $this->getCurrencyNumeric(),
            'orderid'       => $this->getOrderId(),
            'md5key'        => $this->getMd5Key(),
            'textreply'     => "yes",
        ];

        return $data;
    }

    public function sendData($data)
    {
        $endpoint = sprintf($this->endpoint, $this->getUsername(), $this->getPassword());
        $http_response = $this->httpClient->send('POST', $endpoint, [], $data);
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }

}
