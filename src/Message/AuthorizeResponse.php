<?php

namespace Omnipay\TBank\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class AuthorizeResponse
 * @package Omnipay\TBank\Message
 */
class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @return bool
     */
    public function isRedirect()
    {
        return array_key_exists('PaymentURL', $this->data) ? true : false;
    }

    /**
     * Get the URL of the payment form to which the client's browser should be redirected.
     *
     * @return mixed|null
     */
    public function getRedirectUrl()
    {
        return array_key_exists('PaymentURL', $this->data) ? $this->data['PaymentURL'] : null;
    }

    /**
     * Get the order number in the payment system. Unique within the system.
     *
     * @return mixed|null
     */
    public function getPaymentId()
    {
        return array_key_exists('PaymentId', $this->data) ? $this->data['PaymentId'] : null;
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return mixed
     */
    public function getRedirectData()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return !array_key_exists('ErrorCode', $this->data) || $this->data['ErrorCode'] == 0;
    }
}
