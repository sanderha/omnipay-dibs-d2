<?php
/**
 * Created by PhpStorm.
 * User: jkh
 * Date: 12/21/17
 * Time: 12:02 PM
 */

namespace Omnipay\DibsD2\Message;

use Guzzle\Http\Message\RequestInterface;

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
        $http_response = $this->httpClient->createRequest(RequestInterface::POST, $this->endpoint, [], $data)->send();
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }
}