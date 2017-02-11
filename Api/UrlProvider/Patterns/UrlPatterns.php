<?php

namespace Everlution\FileJetBundle\Api\UrlProvider\Patterns;

class UrlPatterns
{
    /** @var string */
    protected $public;

    /** @var string */
    protected $publicMutated;

    /**
     * @param String $public
     * @param String $publicMutated
     */
    public function __construct($public, $publicMutated)
    {
        $this->public = $public;
        $this->publicMutated = $publicMutated;
    }

    /**
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @return string
     */
    public function getPublicMutated()
    {
        return $this->publicMutated;
    }
}
