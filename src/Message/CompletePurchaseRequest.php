<?php

namespace Omnipay\DibsD2\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\DibsD2\Helpers\Security;

/**
 * Dummy Authorize Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function setKey1($value)
    {
        return $this->setParameter('key1', $value);
    }

    public function getKey1()
    {
        return $this->getParameter('key1');
    }

    public function setKey2($value)
    {
        return $this->setParameter('key2', $value);
    }

    public function getKey2()
    {
        return $this->getParameter('key2');
    }


    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        // Validation is only for advance mode

        $this->validateHash($data);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    /**
     * Check if response is valid, only for advance mode
     *
     * @example data = [fp_paidto, fp_paidby, fp_store, fp_amnt, fp_batchnumber, fp_currency]
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function validateHash(array $data)
    {
        $fpHash = isset($data['authkey']) ? $data['authkey'] : null;

        if (empty($fpHash)) {
            return true;
        }

        $parameters = $this->getParameters();

        $key1 = isset($parameters['key1']) ? $parameters['key1'] : '';
        $key2 = isset($parameters['key2']) ? $parameters['key2'] : '';

        if (empty($key1 || $key2)) {
            throw new \Exception("Invalid or no keys set.!");
        }

//        $response = array(
//            $data['fp_paidto'],
//            $data['fp_paidby'],
//            $data['fp_store'],
//            $data['fp_amnt'],
//            $data['fp_batchnumber'],
//            $data['fp_currency'],
//            $secret
//        );

        $hash = Security::getHash($key2, $key1, $response);

        if (strcmp($fpHash, $hash) !== 0) {
            throw new \Exception("Invalid response! Secret key is wrong!");
        }

        return true;
    }
}
