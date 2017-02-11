<?php

namespace Everlution\FileJetBundle\Api;

use Everlution\FileJetBundle\Api\RequestFormatProvider\UploadRequest;
use Everlution\FileJetBundle\Api\RequestFormatProvider\UploadResponse;

interface RequestFormatProvider
{
    /**
     * @param UploadRequest $uploadRequest
     * @return UploadResponse
     */
    public function explicitUpload(UploadRequest $uploadRequest);

    /**
     * @param UploadRequest $uploadRequest
     * @return UploadResponse
     */
    public function uniqueUpload(UploadRequest $uploadRequest);
}
