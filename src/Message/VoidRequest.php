<?php
/**
 * Created by PhpStorm.
 * User: jkh
 * Date: 12/19/17
 * Time: 3:52 PM
 */

namespace Omnipay\DibsD2\Message;

class VoidRequest extends GeneralRequest
{
    public $endpoint = 'https://%s:%s@payment.architrade.com/cgi-adm/cancel.cgi';

    public function getData()
    {
        $data = [
            'merchant'      => $this->getMerchantId(),
            'md5key'        => $this->getMd5Key(),
            'transact'      => $this->getTransactionId(),
            'orderid'       => $this->getOrderId(),
            'textreply'     => "yes",
        ];

        return $data;
    }

    public function sendData($data)
    {
        $endpoint = sprintf($this->endpoint, $this->getUsername(), $this->getPassword());
        $http_response = $this->httpClient->post($endpoint, ['Content-type' => 'text/plain'], http_build_query($data));
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }

}
