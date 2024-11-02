<?php

namespace Omnipay\TBank\Message;

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

/**
 * Class AbstractRequest
 * @package Omnipay\TBank\Message
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * Method name from bank API
     *
     * @return string
     */
    abstract public function getMethod();

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
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setEndPoint($endPoint)
    {
        return $this->setParameter('endPoint', $endPoint);
    }

    /**
     * Set gateway password of merchant account
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->getParameter('Password');
    }

    /**
     * Set gateway password of merchant account
     *
     * @param $password
     * @return BaseAbstractRequest
     */
    public function setPassword($password)
    {
        return $this->setParameter('Password', $password);
    }

    /**
     * Get gateway password of merchant
     *
     * @return mixed
     */
    public function getTerminalKey()
    {
        return $this->getParameter('TerminalKey');
    }

    /**
     * Set gateway userName of merchant
     *
     * @param $value
     * @return BaseAbstractRequest
     */
    public function setTerminalKey($value)
    {
        return $this->setParameter('TerminalKey', $value);
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
     */
    public function setOrderId($value)
    {
        return $this->setParameter('OrderId', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('Amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('Amount', $value !== null ? (string) $value : null);
    }

    public function getDescription()
    {
        return $this->getParameter('Description');
    }

    public function setDescription($value)
    {
        return $this->setParameter('Description', $value !== null ? (string) $value : null);
    }

    /**
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->getParameter('PaymentId');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     * @throws \Omnipay\Common\Exception\RuntimeException
     */
    public function setPaymentId($value)
    {
        return $this->setParameter('PaymentId', $value);
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * Get Request headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return [
            "content-type" => 'application/json'
        ];
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * TBank acquiring request the currency in the minimal payment units
     *
     * @return int
     */
    public function getCurrencyDecimalPlaces()
    {
        return 0;
    }

    /**
     * @param mixed $data
     * @return object|\Omnipay\Common\Message\ResponseInterface
     * @throws \ReflectionException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data)
    {
        $url = $this->getEndPoint() . $this->getMethod();
        $this->validate('TerminalKey', 'Password');

        $requestBody = array_merge(
            [
                'TerminalKey' => $this->getTerminalKey(),
                'Token' => hash(
                    'sha256',
                    collect(array_merge(
                        [
                            'TerminalKey' => $this->getTerminalKey(),
                            'Password' => $this->getPassword(),
                        ],
                        $data
                    ))->filter(
                        fn($value) => !is_array($value)
                    )->sortKeys()->join(''))
            ],
            $data
        );

        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $url,
            $this->getHeaders(),
            json_encode($requestBody, JSON_UNESCAPED_UNICODE)
        );

        $responseClassName = str_replace('Request', 'Response', \get_class($this));
        $reflection = new \ReflectionClass($responseClassName);
        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(
                'Class ' . str_replace('Request', 'Response', \get_class($this)) . ' not found'
            );
        }

        $content = json_decode($httpResponse->getBody()->getContents(), true);

        return $reflection->newInstance($this, $content);
    }

    /**
     * Add additional params to data
     *
     * @param array $data
     * @param array $additionalParams
     * @return array
     */
    public function specifyAdditionalParameters(array $data, array $additionalParams): array
    {
        foreach ($additionalParams as $param) {
            $method = 'get' . ucfirst($param);
            if (method_exists($this, $method)) {
                $value = $this->{$method}();
                if ($value) {
                    $data[$param] = $value;
                }
            }
        }
        return $data;
    }
}
