<?php

namespace Everlution\FileJetBundle\Entity;

use Everlution\FileJetBundle\Api\Common\IdentifiableFile;

interface File extends IdentifiableFile
{
    /**
     * @return string|null
     */
    public function getFileMutation();
}
