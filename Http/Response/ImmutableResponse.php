<?php

namespace Everlution\FileJetBundle\Http\Response;

use Everlution\FileJetBundle\Http\Request\Request;

class ImmutableResponse implements Response
{
    /** @var Request */
    protected $request;

    /** @var int */
    protected $statusCode;

    /** @var array */
    protected $headers;

    /** @var string */
    protected $body;

    /**
     * @param Request $request
     * @param int $statusCode
     * @param mixed $body
     * @param array $headers
     */
    public function __construct(Request $request, $statusCode, $body, array $headers)
    {
        $this->request = $request;
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return static
     */
    public function setRequest(Request $request)
    {
        return new static($request, $this->statusCode, $this->body, $this->headers);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return static
     */
    public function setStatusCode($statusCode)
    {
        return new static($this->request, $statusCode, $this->body, $this->headers);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return static
     */
    public function setBody($body)
    {
        return new static($this->request, $this->statusCode, $body, $this->headers);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return static
     */
    public function setHeaders(array $headers)
    {
        return new static($this->request, $this->statusCode, $this->body, $headers);
    }
}
