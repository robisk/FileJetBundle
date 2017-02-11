<?php

namespace Everlution\FileJetBundle\Http\Request;

class ImmutableRequest implements Request
{
    /** @var string */
    protected $method;

    /** @var string */
    protected $url;

    /** @var array */
    protected $query;

    /** @var string */
    protected $body;

    /** @var array */
    protected $headers;

    /**
     * @param string $method
     * @param string $url
     * @param array $query
     * @param string|null $body
     * @param array $headers
     */
    public function __construct($method, $url, array $query = [], $body = null, array $headers = [])
    {
        $this->trySetMethod($method);
        $this->url = $url;
        $this->query = $query;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @param string $method
     * @throws \InvalidArgumentException
     */
    private function trySetMethod($method)
    {
        if ($method !== static::METHOD_PUT &&
            $method !== static::METHOD_GET &&
            $method !== static::METHOD_POST &&
            $method !== static::METHOD_PATCH &&
            $method !== static::METHOD_DELETE) {
            throw new \InvalidArgumentException('Unsupported method!');
        }

        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return static
     */
    public function setMethod($method)
    {
        return new static($method, $this->url, $this->query, $this->body, $this->headers);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return static
     */
    public function setUrl($url)
    {
        return new static($this->method, $url, $this->query, $this->body, $this->headers);
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param array $query
     * @return static
     */
    public function setQuery(array $query)
    {
        return new static($this->method, $this->url, $query, $this->body, $this->headers);
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
        return new static($this->method, $this->url, $this->query, $this->body, $headers);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string|null $body
     * @return static
     */
    public function setBody($body = null)
    {
        return new static($this->method, $this->url, $this->query, $body, $this->headers);
    }

    /**
     * @return bool
     */
    public function hasBody()
    {
        return $this->body !== null;
    }
}
