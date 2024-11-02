<?php

namespace Omnipay\TBank;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\BadMethodCallException;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\TBank\Message\AuthorizeRequest;
use Omnipay\TBank\Message\BindCardRequest;
use Omnipay\TBank\Message\CaptureRequest;
use Omnipay\TBank\Message\ExtendedOrderStatusRequest;
use Omnipay\TBank\Message\GetBindingsRequest;
use Omnipay\TBank\Message\GetLastOrdersForMerchantsRequest;
use Omnipay\TBank\Message\OrderStatusRequest;
use Omnipay\TBank\Message\PurchaseRequest;
use Omnipay\TBank\Message\RefundRequest;
use Omnipay\TBank\Message\UnBindCardRequest;
use Omnipay\TBank\Message\UpdateSSLCardListRequest;
use Omnipay\TBank\Message\VerifyEnrollmentRequest;
use Omnipay\TBank\Message\VoidRequest;

/**
 * Class Gateway
 * @package Omnipay\TBank
 */
class Gateway extends AbstractGateway
{
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'TBank';
    }

    /**
     * Define gateway parameters, in the following format:
     *
     * array(
     *     'username' => '', // string variable
     *     'testMode' => false, // boolean variable
     *     'landingPage' => array('billing', 'login'), // enum variable, first item is default
     * );
     */
    public function getDefaultParameters()
    {
        return [
            'TerminalKey' => '',
            'Password' => '',
            'endPoint' => 'https://securepay.tinkoff.ru/v2/',
        ];
    }

    public function setTestMode($testMode)
    {
        /*$this->setEndPoint(
            $testMode ? 'https://rest-api-test.tinkoff.ru/v2/' : $this->getDefaultParameters()['endPoint']
        );*/

        return $this->setParameter('testMode', $testMode);
    }

    /**
     * Get endpoint URL
     *
     * @return string
     */
    public function getEndPoint()
    {
        return $this->getParameter('endPoint');
    }

    /**
     * Set endpoint URL
     *
     * @param string $endPoint
     * @return $this
     */
    public function setEndPoint($endPoint)
    {
        return $this->setParameter('endPoint', $endPoint);
    }

    /**
     * Get gateway user name
     *
     * @return string
     */
    public function getTerminalKey()
    {
        return $this->getParameter('TerminalKey');
    }

    /**
     * Set gateway user name
     *
     * @param string $userName
     * @return $this
     */
    public function setTerminalKey($userName)
    {
        return $this->setParameter('TerminalKey', $userName);
    }

    /**
     * Get gateway password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('Password');
    }

    /**
     * Set gateway password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        return $this->setParameter('Password', $password);
    }

    /**
     * Get language of responses
     *
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('Language');
    }

    /**
     * Set language of responses
     *
     * @param $value
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('Language', $value);
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->getParameter('OrderId');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOrderId($value)
    {
        return $this->setParameter('OrderId', $value);
    }

    public function getSuccessURL()
    {
        return $this->getParameter('SuccessURL');
    }

    public function setSuccessURL($value)
    {
        return $this->setParameter('SuccessURL', $value);
    }

    public function getFailURL()
    {
        return $this->getParameter('FailURL');
    }

    public function setFailURL($value)
    {
        return $this->setParameter('FailURL', $value);
    }

    /**
     * Request for order registration with pre-authorization
     *
     * @param array $options array of options
     * @return RequestInterface
     */
    public function authorize(array $options = []): RequestInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    /**
     * Request for order registration
     *
     * @param array $options array of options
     * @return RequestInterface
     */
    public function purchase(array $options = []): RequestInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    /**
     * Order status request
     *
     * @param array $options
     * @return RequestInterface
     */
    public function completeAuthorize(array $options = []): RequestInterface
    {
        return $this->createRequest(OrderStatusRequest::class, $options);
    }

    /**
     * Refund order request
     *
     * @param array $options
     * @return RequestInterface
     */
    public function refund(array $options = []): RequestInterface
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    /**
     * Order status request
     *
     * @param array $options
     * @return RequestInterface
     */
    public function completePurchase(array $options = [])
    {
        return $this->createRequest(OrderStatusRequest::class, $options);
    }

    /**
     * Order cancellation request
     *
     * @param array $options
     * @return RequestInterface
     */
    public function void(array $options = []): RequestInterface
    {
        return $this->createRequest(VoidRequest::class, $options);
    }

    /**
     * Order completion payment request
     *
     * @param array $options
     * @return RequestInterface
     */
    public function capture(array $options = []): RequestInterface
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    /**
     * Order status request
     *
     * @param array $options
     * @return RequestInterface
     */
    public function orderStatus(array $options = []): RequestInterface
    {
        return $this->createRequest(OrderStatusRequest::class, $options);
    }

    /**
     * Supports orderStatus
     *
     * @return boolean True if this gateway supports the status() method
     */
    public function supportsOrderStatus()
    {
        return method_exists($this, 'orderStatus');
    }

    /**
     * Extended order status request
     *
     * @param array $options
     * @return RequestInterface
     */
    public function extendedOrderStatus(array $options = []): RequestInterface
    {
        return $this->createRequest(ExtendedOrderStatusRequest::class, $options);
    }

    /**
     * Supports extendedOrderStatus
     *
     * @return boolean True if this gateway supports the extendedOrderStatus() method
     */
    public function supportsExtendedOrderStatus()
    {
        return method_exists($this, 'extendedOrderStatus');
    }

    /**
     * Request to verify the involvement of the card in 3DS
     *
     * @param array $options
     * @return RequestInterface
     */
    public function verifyEnrollment(array $options = []): RequestInterface
    {
        return $this->createRequest(VerifyEnrollmentRequest::class, $options);
    }

    /**
     * Supports verifyEnrollment
     *
     * @return boolean True if this gateway supports the verifyEnrollment() method
     */
    public function supportsVerifyEnrollment()
    {
        return method_exists($this, 'verifyEnrollment');
    }

    /**
     * Requesting statistics on payments for the period
     *
     * @param array $options
     * @return RequestInterface
     */
    public function getLastOrdersForMerchants(array $options = []): RequestInterface
    {
        return $this->createRequest(GetLastOrdersForMerchantsRequest::class, $options);
    }

    /**
     * Supports getLastOrdersForMerchants
     *
     * @return boolean True if this gateway supports the getLastOrdersForMerchants() method
     */
    public function supportsGetLastOrdersForMerchants()
    {
        return method_exists($this, 'getLastOrdersForMerchants');
    }

    /**
     * Request to add a card to the list of SSL-cards
     *
     * @param array $options
     * @return RequestInterface
     */
    public function updateSSLCardList(array $options = []): RequestInterface
    {
        return $this->createRequest(UpdateSSLCardListRequest::class, $options);
    }

    /**
     * Supports updateSSLCardList
     *
     * @return boolean True if this gateway supports the updateSSLCardList() method
     */
    public function supportsUpdateSSLCardList()
    {
        return method_exists($this, 'updateSSLCardList');
    }

    /**
     * Request for activation of a bundle
     *
     * @param array $options
     * @return RequestInterface
     */
    public function bindCard(array $options = []): RequestInterface
    {
        return $this->createRequest(BindCardRequest::class, $options);
    }

    /**
     * Supports bindCard
     *
     * @return boolean True if this gateway supports the bindCard() method
     */
    public function supportsBindCard()
    {
        return method_exists($this, 'bindCard');
    }

    /**
     * Request for deactivation of a bundle
     *
     * @param array $options
     * @return RequestInterface
     */
    public function unBindCard(array $options = []): RequestInterface
    {
        return $this->createRequest(UnBindCardRequest::class, $options);
    }

    /**
     * Supports unBindCard
     *
     * @return boolean True if this gateway supports the unBindCard() method
     */
    public function supportsUnBindCard()
    {
        return method_exists($this, 'unBindCard');
    }

    /**
     * Request a list of binders by client ID
     *
     * @param array $options
     * @return RequestInterface
     */
    public function getBindings(array $options = []): RequestInterface
    {
        return $this->createRequest(GetBindingsRequest::class, $options);
    }

    /**
     * Supports getBindings
     *
     * @return boolean True if this gateway supports the getBindings() method
     */
    public function supportsGetBindings()
    {
        return method_exists($this, 'getBindings');
    }

    /**
     * {@inheritdoc}
     */
    public function deleteCard(array $options = []): RequestInterface
    {
        throw new BadMethodCallException('Method deleteCard() not supported');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDeleteCard()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function createCard(array $options = []): RequestInterface
    {
        throw new BadMethodCallException('Method createCard() not supported');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsCreateCard()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function updateCard(array $options = []): RequestInterface
    {
        throw new BadMethodCallException('Method updateCard() not supported');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsUpdateCard()
    {
        return false;
    }
}
