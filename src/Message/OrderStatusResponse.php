<?php

namespace Omnipay\TBank\Message;

/**
 * Class OrderStatusResponse
 * @package Omnipay\TBank\Message
 */
class OrderStatusResponse extends AbstractResponse
{
    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        return array_key_exists('Message', $this->data) ? $this->data['Message'] : null;
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return array_key_exists('ErrorCode', $this->data) ? $this->data['ErrorCode'] : null;
    }

    /**
     * Order status
     *
     * The value of this parameter determines the status of the order in the payment system.
     * Missing if the order was not found.
     *
     * @return int
     */
    public function getOrderStatus()
    {
        return array_key_exists('Status', $this->data) ? $this->data['Status'] : null;
    }

    /**
     * Number (identifier) of the order in the store system
     *
     * @return mixed
     */
    public function getOrderId()
    {
        return array_key_exists('OrderId', $this->data) ? $this->data['OrderId'] : null;
    }

    /**
     * The amount of payment in kopecks (or cents)
     *
     * @return int
     */
    public function getAmount()
    {
        return array_key_exists('Amount', $this->data) ? $this->data['Amount'] : null;
    }

    /**
     * {@inheritdoc}
     *
     * @see https://www.tbank.ru/kassa/dev/payments/index.html#tag/Scenarii-oplaty-po-karte/Statusnaya-model-platezha
     */
    public function isSuccessful()
    {
        return in_array($this->getOrderStatus(), [
            'AUTHORIZED',
            'CONFIRMING',
            'CONFIRMED',
        ]);
    }
}
