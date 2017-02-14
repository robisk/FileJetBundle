<?php

namespace Everlution\FileJetBundle\Api\UrlProvider;

use Everlution\FileJetBundle\Api\Common\IdentifiableFile;
use Everlution\FileJetBundle\Api\Common\StorageUtils;
use Everlution\FileJetBundle\Api\UrlProvider;
use Everlution\FileJetBundle\Api\UrlProvider\Patterns\UrlPatterns;
use Everlution\FileJetBundle\Http\Request\ImmutableRequest;
use Everlution\FileJetBundle\Http\Request\Request;
use Everlution\FileJetBundle\Http\RequestSender\RequestSender;
use Everlution\FileJetBundle\Storage\Storages;

class RemoteUrlProvider implements UrlProvider
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
     * @param IdentifiableFile $file
     * @return string
     */
    public function getPublicUrl(IdentifiableFile $file)
    {
        $storage = $this->storages->getByName($file->getFileStorageName());
        $path = '/file/' . $this->toEncodedFileIdentifier($file, $storage) . '/url';

        $request = new ImmutableRequest(Request::METHOD_GET, $path);
        $storageRequest = $this->toStorageRequest($request, $storage);

        return $this->fetchUrl($storageRequest);
    }

    /**
     * @param IdentifiableFile $file
     * @param string $mutation
     * @param int|null $ttl
     * @return string
     */
    public function getPublicMutatedUrl(IdentifiableFile $file, $mutation, $ttl = null)
    {
        $storage = $this->storages->getByName($file->getFileStorageName());
        $path = '/file/' . $this->toEncodedFileIdentifier($file, $storage) . '/url/' . urlencode($mutation);

        $request = new ImmutableRequest(Request::METHOD_GET, $path);
        if ($ttl !== null) {
            $request = $request->setQuery(['ttl' => $ttl]);
        }

        $storageRequest = $this->toStorageRequest($request, $storage);
        return $this->fetchUrl($storageRequest);
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function fetchUrl(Request $request)
    {
        $response = $this->requestSender->send($request);
        return $response->getBody()->url;
    }

    /**
     * @param string $storageName
     * @return UrlPatterns
     */
    public function getUrlPatterns($storageName)
    {
        $path = '/url/patterns';
        $storage = $this->storages->getByName($storageName);

        $request = new ImmutableRequest(Request::METHOD_GET, $path);
        $storageRequest = $this->toStorageRequest($request, $storage);

        $response = $this->requestSender->send($storageRequest);
        $responseBody = $response->getBody();

        return new UrlPatterns($responseBody->public, $responseBody->publicMutated);
    }
}
