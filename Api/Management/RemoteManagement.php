<?php

namespace Everlution\FileJetBundle\Api\Management;

use Everlution\FileJetBundle\Api\Common\IdentifiableFile;
use Everlution\FileJetBundle\Api\Common\StorageRequests;
use Everlution\FileJetBundle\Api\Management;
use Everlution\FileJetBundle\Http\Request\ImmutableRequest;
use Everlution\FileJetBundle\Http\Request\Request;
use Everlution\FileJetBundle\Http\RequestSender\RequestSender;
use Everlution\FileJetBundle\Storage\Storages;

class RemoteManagement implements Management
{
    use StorageRequests;

    /** @var Storages */
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
     */
    public function deleteFile(IdentifiableFile $file)
    {
        $storage = $this->storages->getByName($file->getFileStorageName());
        $path = '/file/' . urlencode($file->getFileIdentifier());

        $request = new ImmutableRequest(Request::METHOD_DELETE, $path);
        $storageRequest = $this->toStorageRequest($request, $storage);

        $this->requestSender->send($storageRequest);
    }
}
