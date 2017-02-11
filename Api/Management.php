<?php

namespace Everlution\FileJetBundle\Api;

use Everlution\FileJetBundle\Api\Common\IdentifiableFile;

interface Management
{
    /**
     * @param IdentifiableFile $file
     */
    public function deleteFile(IdentifiableFile $file);
}
