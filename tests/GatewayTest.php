<?php

namespace Omnipay\DibsD2;

use Omnipay\Common\CreditCard;
use Omnipay\DibsD2\Message\AuthorizeRequest;
use Omnipay\DibsD2\Message\CaptureRequest;
use Omnipay\DibsD2\Message\CompleteRequest;
use Omnipay\DibsD2\Message\PurchaseRequest;
use Omnipay\DibsD2\Message\RedirectResponse;
use Omnipay\DibsD2\Message\RefundRequest;
use Omnipay\DibsD2\Message\VoidRequest;
use Omnipay\DibsD2\Message\PostResponse;
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

    /**
     * @var CreditCard
     */
    protected $card;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setLang('da');
        $this->gateway->setMerchantId('123');
        $this->gateway->setTestMode(true);
        $this->gateway->setKey1('key1');
        $this->gateway->setKey2('key2');
        $this->gateway->setPassword('password');
        $this->gateway->setUsername('username');

        $card = new CreditCard($this->getValidCard());
        $card->setBillingAddress1('Wall street');
        $card->setBillingAddress2('Wall street 2');
        $card->setBillingCity('San Luis Obispo');
        $card->setBillingCountry('US');
        $card->setBillingPostcode('93401');
        $card->setBillingPhone('1234567');
        $card->setBillingState('CA');
        $card->setShippingAddress1('Shipping Wall street');
        $card->setShippingAddress2('Shipping Wall street 2');
        $card->setShippingCity('San Luis Obispo');
        $card->setShippingCountry('US');
        $card->setShippingPostcode('93401');
        $card->setShippingPhone('1234567');
        $card->setShippingState('CA');
        $card->setCompany('Test Business name');
        $card->setEmail('test@example.com');
        $this->card = $card;
    }

    public function testGatewayGetterSetters()
    {
        $this->assertSame('da', $this->gateway->getLang());
        $this->assertSame('123', $this->gateway->getMerchantId());
        $this->assertSame('key1', $this->gateway->getKey1());
        $this->assertSame('key2', $this->gateway->getKey2());
        $this->assertSame('password', $this->gateway->getPassword());
        $this->assertSame('username', $this->gateway->getUsername());
    }

    public function testPurchaseWithMd5Check()
    {
        $params = [
            'amount'        => 100.00,
            'currency'      => 'DKK',
            'card'          => $this->card,
            'accepturl'     => 'http:://test.test',
            'callbackurl'   => 'http:://test.test',
        ];
        $response = $this->gateway->purchase($params);
        $this->assertInstanceOf(PurchaseRequest::class, $response);
        $this->assertArrayHasKey('capturenow', $response->getData());

        $request = $response->sendData($response->getData());
        $this->assertInstanceOf(RedirectResponse::class, $request);

        $this->assertSame('POST', $request->getRedirectMethod());
        $this->assertSame($response->endpoint, $request->getRedirectUrl());
        $this->assertTrue($request->isRedirect());
        $this->assertFalse($request->isSuccessful());
        $this->assertSame($response->getData(), $request->getRedirectData());
    }

    public function testPurchaseWithoutMd5Check()
    {
        $this->gateway->setKey1(null);
        $params = [
            'amount'        => 100.00,
            'currency'      => 'DKK',
            'accepturl'     => 'http:://test.test',
            'callbackurl'   => 'http:://test.test',
        ];
        $response = $this->gateway->purchase($params);
        $this->assertInstanceOf(PurchaseRequest::class, $response);
        $this->assertArrayHasKey('capturenow', $response->getData());
        $this->assertArrayNotHasKey('md5key', $response->getData());
    }

    public function testCompletePurchase()
    {
        $this->getHttpRequest()->request->replace(array(
            'transact'  => '250525',
            'amount'    => 100.00,
            'currency'  => '208',
            'authkey'   => '4bf548305937044d73d30b952cfcb22e',
        ));
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf(CompleteRequest::class, $request);

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('250525', $response->getTransactionReference());
    }

    public function testCompletePurchaseWithNoMd5()
    {
        $this->gateway->setKey1(null);
        $this->getHttpRequest()->request->replace(array(
            'transact'  => '250525',
            'amount'    => 100.00,
            'currency'  => '208'
        ));
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf(CompleteRequest::class, $request);

        $response = $request->send();
        $this->assertSame('250525', $response->getTransactionReference());
    }

    public function testCompletePurchaseWithMd5Failure()
    {
        $this->getHttpRequest()->request->replace(array(
            'transact'  => '250525',
            'amount'    => 100.00,
            'currency'  => '208',
            'authkey'   => 'fail',
        ));
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf(CompleteRequest::class, $request);

        $this->setExpectedException(\UnexpectedValueException::class);
        $request->send();
    }

    public function testAuthorize()
    {
        $params = [
            'amount'        => 100.00,
            'currency'      => 'DKK',
            'card'          => $this->card,
            'accepturl'     => 'http:://test.test',
            'callbackurl'   => 'http:://test.test',
        ];
        $response = $this->gateway->authorize($params);
        $this->assertInstanceOf(AuthorizeRequest::class, $response);

        $this->assertInstanceOf(RedirectResponse::class, $response->send());
    }

    public function testCompleteAuthorize()
    {
        $response = $this->gateway->completeAuthorize();
        $this->assertInstanceOf(CompleteRequest::class, $response);
    }

    public function testCapture()
    {
        $params = [
            'amount'        => 100.00,
            'transact'      => '250525',
            'orderid'       => 6,
        ];
        $request = $this->gateway->capture($params);
        $this->assertInstanceOf(CaptureRequest::class, $request);

        $this->setMockHttpResponse('PostResponseAccepted.txt');
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testCaptureFailed()
    {
        $params = [
            'amount'        => 100.00,
            'transact'      => '250525',
            'orderid'       => 6,
        ];
        $request = $this->gateway->capture($params);
        $this->assertInstanceOf(CaptureRequest::class, $request);

        $this->setMockHttpResponse('PostResponseFailed.txt');
        $response = $request->send();

        $this->assertFalse($response->isSuccessful());
    }

    public function provideErrorResponses()
    {
        return [
            ['1', 'No response from acquirer'],
            ['2', 'Timeout'],
            ['3', 'Credit card expired'],
            ['4', 'Rejected by acquirer'],
            ['5', 'Authorisation older than 7 days'],
            ['6', 'Transaction status on the DIBS server does not allow function'],
            ['7', 'Amount too high'],
            ['8', 'Error in the parameters sent to the DIBS server. An additional parameter called "message" is returned, with a value that may help identifying the error'],
            ['9', 'Order number (orderid) does not correspond to the authorisation order number'],
            ['10', 'Re-authorisation of the transaction was rejected'],
            ['11', 'Not able to communicate with the acquier'],
            ['12', 'Confirm request error'],
            ['14', 'Capture is called for a transaction which is pending for batch - i.e. capture was already called'],
            ['15', 'Capture or refund was blocked by DIBS'],
            ['100', 'Unknown error'],
        ];
    }

    /**
     * @dataProvider provideErrorResponses
     */
    public function testPostResponseErrorCodes($errorCode, $responseText)
    {
        // This is just a dummy request.
        $request = $this->gateway->capture();
        $output = [
            'status'    => 'FAILED',
            'reason'    => $errorCode
        ];
        $postResponse = new PostResponse($request, $output);
        $this->assertSame($responseText, $postResponse->getError());
    }

    public function testVoid()
    {
        $params = [
            'amount'        => 100.00,
            'transact'      => '250525',
            'orderid'       => 6,
        ];
        $request = $this->gateway->void($params);
        $this->assertInstanceOf(VoidRequest::class, $request);

        $this->setMockHttpResponse('PostResponseAccepted.txt');
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testRefund()
    {
        $params = [
            'amount'        => 100.00,
            'transact'      => '250525',
            'orderid'       => 6,
        ];
        $request = $this->gateway->refund($params);
        $this->assertInstanceOf(RefundRequest::class, $request);

        $this->setMockHttpResponse('PostResponseAccepted.txt');
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

}
