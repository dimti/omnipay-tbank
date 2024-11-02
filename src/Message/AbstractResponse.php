<?php

namespace Omnipay\TBank\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class AbstractResponse
 * @package Omnipay\TBank\Message
 */
abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return sprintf(
            '%s: %s',
            array_key_exists('Message', $this->data) ? $this->data['Message'] : null,
            array_key_exists('Details', $this->data) ? $this->data['Details'] : null
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return array_key_exists('ErrorCode', $this->data) ? $this->data['ErrorCode'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return $this->getCode() == 0;
    }
}
