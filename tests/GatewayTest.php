<?php

namespace Omnipay\DibsD2;

use Omnipay\DibsD2\Helpers\Security;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class GatewayTest
 * @package Omnipay\DibsD2
 *
 */
class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\DibsD2\Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchaseGetterSetters()
    {
        $request = $this->gateway->purchase();

        $request->setTestMode(true);
        $request->setAccepturl('http://test.tst');
        $request->setCallbackurl('http://test.tst');
        $request->setCurrency('DKK');
        $request->setMerchant('123123');
        $request->setOrderid('1');
        $request->setBillingAddress('Road of roads');
        $request->setBillingAddress2('More roads of roads');
        $request->setBillingFirstName('First');
        $request->setBillingLastName('Last');
        $request->setBillingPostalCode('9000');
        $request->setCardholder_name('Card Holder');
        $request->setCardholder_address1('card holder address');
        $request->setCardholder_zipcode('8000');

        $this->assertTrue($request->getTestMode());
        $this->assertEquals($request->getAccepturl(), 'http://test.tst');


    }

    public function testPurchaseSimpleMode()
    {
        $purchaseOptions = array(
            'accepturl' => 'http://test.tst',
            'amount' => 10000,
            'callbackurl' => 'http://test.tst',
            'currency' => 752,
            'merchant' => '1245748',
            'orderid' => '1',
        );

        $request = $this->gateway->purchase($purchaseOptions);
        $response = $request->send();
        $redirectUrl = $response->getRedirectUrl();
        $redirectData = $response->getRedirectData();

        //Response validation
        $this->assertEquals('POST', $response->getRedirectMethod());
        $this->assertTrue(!empty($redirectUrl));
        $this->assertTrue($response->isRedirect());
        $this->assertTrue(!$response->isSuccessful());
        $this->assertTrue(!empty($redirectData));
    }

    public function testPurchaseAdvanceMode()
    {
        $purchaseOptions = array(
            'accepturl' => 'http://test.tst',
            'amount' => 10000,
            'callbackurl' => 'http://test.tst',
            'currency' => 752,
            'merchant' => '1245748',
            'orderid' => '1',
        );

        $request = $this->gateway->purchase($purchaseOptions);
        $response = $request->send();

        //Response validation
        $this->assertTrue($response->isRedirect());
        $this->assertTrue(!$response->isSuccessful());
    }

    public function testCompletePurchaseSimpleModeSuccess()
    {
        $responseParams = array(
            'acquirer' => 'VISA',
            'agreement' => '1245748',
            'amount' => 10000,
            'approvalcode' => '',
            'authkey' => 'IDR',
            'cardcountry' => 'DK',
            'cardexpdate' => '1901',
            'cardId' => '1e2adf1f2f3643706e829e48a962b1e9',
            'cardnomask' => 'XXXXXXXXXXXX1234',
            'cardprefix' => '123456',
            'checksum' => '831c752681bfc057744155a540f5346f',
            'currency' => 'DKK',
            'fee' => '0',
            'orderid' => '1',
            'paytype' => 'VISA',
            'severity' => '1',
            'statuscode' => '2',
            'suspect' => 'false',
            'threeDstatus' => '0',
            'transact' => '123456',
        );
        $request = $this->gateway->completePurchase($responseParams);
        $response = $request->sendData($responseParams);

        //Response validation
        $this->assertTrue($response->isSuccessful());
        $this->assertSame($response->getTransactionReference(), $responseParams['orderid']);
        $this->assertTrue(sizeof($request->getData()) == 0);
    }

    public function testCompletePurchaseAdvanceModeSuccess()
    {
        $hash = Security::getHash('asdf', 'dsfa', [
            'merchant'  => '123456',
            'orderid'   => 1,
            'currency'  => 'DKK',
            'amount'    => 10000
        ]);

        $responseParams = array(
            'acquirer' => 'VISA',
            'agreement' => '1245748',
            'amount' => 10000,
            'approvalcode' => '',
            'authkey' => $hash,
            'cardcountry' => 'DK',
            'cardexpdate' => '1901',
            'cardId' => '1e2adf1f2f3643706e829e48a962b1e9',
            'cardnomask' => 'XXXXXXXXXXXX1234',
            'cardprefix' => '123456',
            'checksum' => '831c752681bfc057744155a540f5346f',
            'currency' => 'DKK',
            'fee' => '0',
            'orderid' => '1',
            'paytype' => 'VISA',
            'severity' => '1',
            'statuscode' => '2',
            'suspect' => 'false',
            'threeDstatus' => '0',
            'transact' => '123456',
        );

        $request = $this->gateway->completePurchase($responseParams);
        $this->gateway->setKey1('asdf');
        $this->gateway->setKey2('dsfa');
        $response = $request->sendData($responseParams);

        //Response validation
        $this->assertTrue($response->isSuccessful());
        $this->assertSame($response->getTransactionReference(), $responseParams['orderid']);
        var_dump($response);die;

        // $this->assertSame($hash, $request->getSecret());
    }

    /**
     * @expectedException        \Exception
     * @expectedExceptionMessage Invalid response
     */
    public function CompletePurchaseAdvanceModeInvalidHash()
    {
        $responseParams = array(
            'fp_paidto' => 'FP000001',
            'fp_paidby' => 'FP000002',
            'fp_amnt' => 1000,
            'fp_fee_amnt' => 1000,
            'fp_currency' => 'IDR',
            'fp_batchnumber' => 'DDDADS234234',
            'fp_store' => 'my store',
            'fp_timestamp' => date('Y-m-d H:i:s'),
            'fp_merchant_ref' => '1311059195',
            'fp_hash' => 'xxxx'
        );

        $request = $this->gateway->completePurchase($responseParams);
        $request->setSecret(5000);
        $request->sendData($responseParams);
    }

    /**
     * @expectedException        \Exception
     * @expectedExceptionMessage Secret key is required!
     */
    public function CompletePurchaseAdvanceModeMissingSecret()
    {
        $responseParams = array(
            'fp_paidto' => 'FP000001',
            'fp_paidby' => 'FP000002',
            'fp_amnt' => 1000,
            'fp_fee_amnt' => 1000,
            'fp_currency' => 'IDR',
            'fp_batchnumber' => 'DDDADS234234',
            'fp_store' => 'my store',
            'fp_timestamp' => date('Y-m-d H:i:s'),
            'fp_merchant_ref' => '1311059195',
            'fp_hash' => 'xxxx'
        );

        $request = $this->gateway->completePurchase($responseParams);
        $request->sendData($responseParams);
    }
}
