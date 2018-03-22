<?php
/**
 * Created by PhpStorm.
 * User: jkh
 * Date: 12/19/17
 * Time: 3:52 PM
 */

namespace Omnipay\DibsD2\Message;

use Guzzle\Http\Message\RequestInterface;

class VoidRequest extends GeneralRequest
{
    public $endpoint = 'https://%s:%s@payment.architrade.com/cgi-adm/cancel.cgi';

    public function getData()
    {
        $data = [
            'merchant'      => $this->getMerchantId(),
            'md5key'        => $this->getMd5Key(),
            'transact'      => $this->getTransactionReference(),
            'orderid'       => $this->getTransactionId(),
            'textreply'     => "yes",
        ];

        return $data;
    }

    public function sendData($data)
    {
        $endpoint = sprintf($this->endpoint, $this->getUsername(), $this->getPassword());
        $http_response = $this->httpClient->createRequest(RequestInterface::POST, $endpoint, [], $data)->send();
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }

}
