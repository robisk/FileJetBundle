<?php

namespace Everlution\FileJetBundle\Api\RequestFormatProvider;

use Everlution\FileJetBundle\Api\Common\StorageUtils;
use Everlution\FileJetBundle\Api\RequestFormatProvider;
use Everlution\FileJetBundle\Http\Request\ImmutableRequest;
use Everlution\FileJetBundle\Http\Request\Request;
use Everlution\FileJetBundle\Http\RequestSender\RequestSender;
use Everlution\FileJetBundle\Storage\Storages;

class RemoteRequestFormatProvider implements RequestFormatProvider
{
    use StorageUtils;

    /** @var  Storages */
    protected $storages;

    /** @var RequestSender */
    protected $requestSender;

    /**
     * @param Storages $storages
     * @param RequestSender $requestSender
     */
    public function __construct(Storages $storages, RequestSender $requestSender)
    {
        $this->storages = $storages;
        $this->requestSender = $requestSender;
    }

    /**
     * @param UploadRequest $uploadRequest
     * @return UploadResponse
     */
    public function explicitUpload(UploadRequest $uploadRequest)
    {
        $relativePath = '/request-format/upload/explicit';
        $request = $this->createRequest($relativePath, $uploadRequest);
        return $this->fetchRequestFormat($request);
    }

    /**
     * @param UploadRequest $uploadRequest
     * @return UploadResponse
     */
    public function uniqueUpload(UploadRequest $uploadRequest)
    {
        $relativePath = '/request-format/upload/unique';
        $request = $this->createRequest($relativePath, $uploadRequest);
        return $this->fetchRequestFormat($request);
    }

    /**
     * @param string $relativePath
     * @param UploadRequest $uploadRequest
     * @return Request
     */
    protected function createRequest($relativePath, UploadRequest $uploadRequest)
    {
        $storage = $this->storages->getByName($uploadRequest->getStorageName());

        $body = [
            'path' => $storage->getPrefix() . $uploadRequest->getPath(),
            'expires' => $uploadRequest->getExpires()->getTimestamp() * 1000,
            'contentType' => $uploadRequest->getContentType()
        ];

        $request = new ImmutableRequest(Request::METHOD_POST, $relativePath);
        $request = $request->setBody($body);

        return $this->toStorageRequest($request, $storage);
    }

    /**
     * @param Request $request
     * @return UploadResponse
     */
    protected function fetchRequestFormat(Request $request)
    {
        $response = $this->requestSender->send($request);
        $responseBody = $response->getBody();

        return new UploadResponse(
            $responseBody->identifier,
            $this->createRequestFormat($responseBody->requestFormat)
        );
    }

    /**
     * @param \stdClass $rawRequestFormat
     * @return RequestFormat
     */
    protected function createRequestFormat($rawRequestFormat)
    {
        return new RequestFormat(
            $rawRequestFormat->httpMethod,
            $rawRequestFormat->url,
            (array) $rawRequestFormat->headers
        );
    }
}
