<?php

namespace Everlution\FileJetBundle\Api\RequestFormatProvider;

class UploadRequest
{
    /** @var string */
    protected $storageName;

    /** @var string */
    protected $path;

    /** @var string */
    protected $contentType;

    /** @var \DateTime */
    protected $expires;

    /**
     * @param string $storageName
     * @param string $path
     * @param string $contentType
     * @param \DateTime $expires
     */
    public function __construct($storageName, $path, $contentType, \DateTime $expires)
    {
        $this->storageName = $storageName;
        $this->path = $path;
        $this->contentType = $contentType;
        $this->expires = $expires;
    }

    /**
     * @return string
     */
    public function getStorageName()
    {
        return $this->storageName;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }
}
