<?php

namespace Omnipay\DibsD2\Helpers;

class Security
{

    /**
     * Get md5 hash
     *
     * @param $data
     * @return string
     */
    public static function getHash($key2, $key1, $data)
    {
        $parameter_string = '';
        $parameter_string .= 'merchant=' . $data['merchant'];
        $parameter_string .= '&orderid=' . $data['orderid'];
        $parameter_string .= '&currency=' . $data['currency'];
        $parameter_string .= '&amount=' . $data['amount'];

        return md5($key2 . md5($key1 . $parameter_string) );
    }
}
