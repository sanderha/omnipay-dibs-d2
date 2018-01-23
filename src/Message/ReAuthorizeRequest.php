<?php
/**
 * Created by PhpStorm.
 * User: jkh
 * Date: 12/21/17
 * Time: 12:02 PM
 */

namespace Omnipay\DibsD2\Message;

class ReAuthorizeRequest extends GeneralRequest
{
    public $endpoint = 'https://payment.architrade.com/cgi-bin/reauth.cgi';

    public function getData()
    {
        $data = [
            'merchant'      => $this->getMerchantId(),
            'transact'      => $this->getTransactionId(),
            'textreply'     => "yes",
        ];

        return $data;
    }

    public function sendData($data)
    {
        $http_response = $this->httpClient->post($this->endpoint, ['Content-type' => 'text/plain'], http_build_query($data));
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }
}
