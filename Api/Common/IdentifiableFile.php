<?php

namespace Everlution\FileJetBundle\Api\Common;

interface IdentifiableFile
{
    /**
     * @return string
     */
    public function getFileIdentifier();

    /**
     * @return string
     */
    public function getFileStorageName();
}
