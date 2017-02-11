<?php

namespace Everlution\FileJetBundle\Http\RequestSender;

use Everlution\FileJetBundle\Http\Request\Request;
use Everlution\FileJetBundle\Http\Response\Response;

class RpcRequestSender implements RequestSender
{
    /** @var RequestSender */
    protected $innerSender;

    /**
     * @param RequestSender $innerSender
     */
    public function __construct(RequestSender $innerSender)
    {
        $this->innerSender = $innerSender;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws RpcException
     */
    public function send(Request $request)
    {
        $transformedRequest = $this->transformRequest($request);
        $response = $this->innerSender->send($transformedRequest);

        $transformedResponse = $this->transformResponse($response);
        $this->treatWithPossibleResponseError($transformedResponse);

        return $transformedResponse;
    }

    /**
     * @param Request $request
     * @return Request
     */
    protected function transformRequest(Request $request)
    {
        $headers = $this->getTransformedRequestHeaders($request);
        $body = $this->getTransformedRequestBody($request);
        return $request->setBody($body)->setHeaders($headers);
    }

    /**
     * @param Request $request
     * @return string|number|null
     */
    protected function getTransformedRequestBody(Request $request)
    {
        $body = $request->getBody();
        if (is_array($body) || is_object($body)) {
            $body = json_encode($body);
        }

        return $body;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getTransformedRequestHeaders(Request $request)
    {
        $headers = $request->getHeaders();
        $headers['Content-type'] = ['application/json'];
        return $headers;
    }

    /**
     * @param Response $response
     * @throws RpcException
     */
    protected function treatWithPossibleResponseError(Response $response)
    {
        if ($response->getStatusCode() >= 400) {
            $message = $response->getBody()->error->message;
            throw new RpcException($message, $response);
        }
    }

    /**
     * @param Response $response
     * @return Response
     */
    protected function transformResponse(Response $response)
    {
        $body = $this->getTransformedResponseBody($response);
        return $response->setBody($body);
    }

    /**
     * @param Response $response
     * @return Object|null
     */
    protected function getTransformedResponseBody(Response $response)
    {
        $body = $response->getBody();
        if ($body === null) {
            return null;
        }

        return json_decode($body);
    }
}
