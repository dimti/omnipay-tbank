<?php

namespace Omnipay\TBank\Message;

use Omnipay\Common\Exception\RuntimeException;

/**
 * Class AuthorizeRequest
 * @package Omnipay\TBank\Message
 */
class AuthorizeRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('OrderId', 'Amount');

        $data = [
            'OrderId' => $this->getOrderId(),
            'Amount' => $this->getAmount(),
        ];

        $additionalParams = [
            'TerminalKey',
            'Description',
            'SuccessURL',
            'FailURL',
//            'DATA',
            'Receipt',
        ];

        return $this->specifyAdditionalParameters($data, $additionalParams);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return 'Init';
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
     * @return \Omnipay\Common\Message\AbstractRequest
     * @throws \Omnipay\Common\Exception\RuntimeException
     */
    public function setOrderId($value)
    {
        return $this->setParameter('OrderId', $value);
    }

    public function getReceipt()
    {
        return $this->getParameter('Receipt');
    }

    public function setReceipt($receipt)
    {
        return $this->setParameter('Receipt', $receipt);
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
}
