<?php

namespace Omnipay\DibsD2\Message;

class CompleteRequest extends GeneralRequest
{

    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        // Validation is only for advance mode
        $this->validateHash($data);

        return $this->response = new CompleteResponse($this, $data);
    }

    private function validateHash($data)
    {
        $key1 = $this->getKey1();
        $key2 = $this->getKey2();

        if (empty($key1) || empty($key2)) {
            return null;
        }

        $parameter_string = '';
        $parameter_string .= 'transact=' . $data['transact'];
        $parameter_string .= '&amount=' . $data['amount'];
        $parameter_string .= '&currency=' . $data['currency'];

        $md5 = md5($key2 . md5($key1 . $parameter_string) );

        if ($md5 !== $data['authkey']) {
            throw new \UnexpectedValueException('MD5 keys does not match');
        }
    }

}
