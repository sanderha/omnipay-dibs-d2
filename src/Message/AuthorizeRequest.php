<?php

namespace Omnipay\DibsD2\Message;


class AuthorizeRequest extends GeneralRequest
{
    public $endpoint = 'https://payment.architrade.com/paymentweb/start.action';

    public function sendData($data)
    {
        return new RedirectResponse($this, $data, $this->endpoint);
    }

}
