<?php

namespace Everlution\FileJetBundle\Api\RequestFormatProvider;

class RequestFormat implements \JsonSerializable
{
    /** @var string */
    protected $httpMethod;

    /** @var string */
    protected $url;

    /** @var array */
    protected $headers;

    /**
     * @param string $httpMethod
     * @param string $url
     * @param array $headers
     */
    public function __construct($httpMethod, $url, array $headers)
    {
        $this->httpMethod = $httpMethod;
        $this->url = $url;
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'httpMethod' => $this->httpMethod,
            'url' => $this->url,
            'headers' => $this->headers,
        ];
    }
}
