<?php

namespace Omnipay\TBank\Message;

/**
 * Class OrderStatusRequest
 * @package Omnipay\TBank\Message
 */
class OrderStatusRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('PaymentId');

        $data = [
            'PaymentId' => $this->getPaymentId()
        ];

        return $this->specifyAdditionalParameters($data, ['Language']);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return 'GetState';
    }
}
