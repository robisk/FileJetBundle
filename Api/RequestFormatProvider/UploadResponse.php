<?php

namespace Everlution\FileJetBundle\Api\RequestFormatProvider;

class UploadResponse implements \JsonSerializable
{
    /** @var string */
    protected $identifier;

    /** @var RequestFormat */
    protected $requestFormat;

    /**
     * @param string $identifier
     * @param RequestFormat $requestFormat
     */
    public function __construct($identifier, RequestFormat $requestFormat)
    {
        $this->identifier = $identifier;
        $this->requestFormat = $requestFormat;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return RequestFormat
     */
    public function getRequestFormat()
    {
        return $this->requestFormat;
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
            'identifier' => $this->identifier,
            'requestFormat' => $this->requestFormat->jsonSerialize()
        ];
    }
}
