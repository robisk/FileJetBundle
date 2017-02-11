<?php

namespace Everlution\FileJetBundle\Http\Response;

use Everlution\FileJetBundle\Http\Request\Request;

interface Response
{
    /**
     * @return Request
     */
    public function getRequest();

    /**
     * @param Request $request
     * @return Response
     */
    public function setRequest(Request $request);

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @param int $statusCode
     * @return Response
     */
    public function setStatusCode($statusCode);

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @param string $body
     * @return Response
     */
    public function setBody($body);

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @param array $headers
     * @return Response
     */
    public function setHeaders(array $headers);
}
