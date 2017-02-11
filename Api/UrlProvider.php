<?php

namespace Everlution\FileJetBundle\Api;

use Everlution\FileJetBundle\Api\Common\IdentifiableFile;
use Everlution\FileJetBundle\Api\UrlProvider\Patterns\UrlPatterns;

interface UrlProvider
{
    /**
     * @param IdentifiableFile $file
     * @return string
     */
    public function getPublicUrl(IdentifiableFile $file);

    /**
     * @param IdentifiableFile $file
     * @param string $mutation
     * @param int|null $ttl
     * @return string
     */
    public function getPublicMutatedUrl(IdentifiableFile $file, $mutation, $ttl);

    /**
     * @param string $storageName
     * @return UrlPatterns
     */
    public function getUrlPatterns($storageName);
}
