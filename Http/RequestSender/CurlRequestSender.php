<?php

namespace Everlution\FileJetBundle\Http\RequestSender;

use Ci\RestClientBundle\Exceptions\CouldntConnectException;
use Ci\RestClientBundle\Services\RestInterface;
use Everlution\FileJetBundle\Http\Response\Response;
use Everlution\FileJetBundle\Http\Response\ImmutableResponse;
use Everlution\FileJetBundle\Http\Request\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CurlRequestSender implements RequestSender
{
    /** @var RestInterface */
    protected $restClient;

    /**
     * @param RestInterface $restClient
     */
    public function __construct(RestInterface $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function send(Request $request)
    {
        try {
            $symfonyResponse = $this->sendRequest($request);
        } catch (CouldntConnectException $e) {
            throw new ServiceUnavailableException();
        }

        return new ImmutableResponse(
            $request,
            $symfonyResponse->getStatusCode(),
            $symfonyResponse->getContent(),
            $symfonyResponse->headers->all()
        );
    }

    /**
     * @param Request $request
     * @return SymfonyResponse
     * @throws \InvalidArgumentException
     */
    protected function sendRequest(Request $request)
    {
        $method = $request->getMethod();
        $fullUrl = $this->createFullUrl($request);
        $body = $request->getBody();
        $additionalOptions = $this->createAdditionalOptions($request);

        switch ($method) {
            case Request::METHOD_GET: return $this->restClient->get($fullUrl, $additionalOptions);
            case Request::METHOD_PUT: return $this->restClient->put($fullUrl, $body, $additionalOptions);
            case Request::METHOD_POST: return $this->restClient->post($fullUrl, $body, $additionalOptions);
            case Request::METHOD_PATCH: return $this->restClient->patch($fullUrl, $body, $additionalOptions);
            case Request::METHOD_DELETE: return $this->restClient->delete($fullUrl, $additionalOptions);
            default: throw new \InvalidArgumentException("Request method [$method] is not supported!");
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function createAdditionalOptions(Request $request)
    {
        $headers = [];
        foreach ($request->getHeaders() as $headerName => $headerValues) {
            foreach ($headerValues as $headerValue) {
                $headers[] = "$headerName: $headerValue";
            }
        }

        return [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false //TODO: Use TRUE
        ];
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function createFullUrl(Request $request)
    {
        $query = $request->getQuery();
        $url = $request->getUrl();

        if (empty($query)) {
            return $url;
        }

        return $url . '?' . http_build_query($query);
    }
}
