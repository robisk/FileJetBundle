<?php

namespace Everlution\FileJetBundle\Http\Request;

interface Request
{
    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';
    const METHOD_POST = 'post';
    const METHOD_PATCH = 'patch';
    const METHOD_DELETE = 'delete';

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @param string $method
     * @return Request
     */
    public function setMethod($method);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return Request
     */
    public function setUrl($url);

    /**
     * @return array
     */
    public function getQuery();

    /**
     * @param array $query
     * @return Request
     */
    public function setQuery(array $query);

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @param array $headers
     * @return Request
     */
    public function setHeaders(array $headers);

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string|null $body
     * @return Request
     */
    public function setBody($body);

    /**
     * @return bool
     */
    public function hasBody();
}
