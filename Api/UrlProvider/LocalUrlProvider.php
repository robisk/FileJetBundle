<?php

namespace Everlution\FileJetBundle\Api\UrlProvider;

use Everlution\FileJetBundle\Api\Common\IdentifiableFile;
use Everlution\FileJetBundle\Api\UrlProvider;
use Everlution\FileJetBundle\Api\UrlProvider\Patterns\UrlPatterns;

class LocalUrlProvider implements UrlProvider
{
    /** @var UrlPatterns[] */
    protected $urlPatterns;

    /**
     * @param UrlPatterns[] $urlPatterns
     */
    public function __construct($urlPatterns)
    {
        $this->urlPatterns = $urlPatterns;
    }

    /**
     * @param IdentifiableFile $file
     * @return string
     */
    public function getPublicUrl(IdentifiableFile $file)
    {
        $pattern = $this->getUrlPatternsByFile($file)->getPublic();
        return $this->substituteInPattern('$identifier', $file->getFileIdentifier(), $pattern);
    }

    /**
     * @param IdentifiableFile $file
     * @param string $mutation
     * @param int $ttl
     * @return string
     */
    public function getPublicMutatedUrl(IdentifiableFile $file, $mutation, $ttl = null)
    {
        $pattern = $this->getUrlPatternsByFile($file)->getPublicMutated();

        $pattern = $this->substituteInPattern('$originIdentifier', $file->getFileIdentifier(), $pattern);
        $pattern = $this->substituteInPattern('$mutation', $mutation, $pattern);
        $pattern = $this->substituteInPattern('$targetFileName', basename($file->getFileIdentifier()), $pattern);

        if ($ttl !== null) {
            $pattern .= "?ttl=$ttl";
        }

        return $pattern;
    }

    /**
     * @param IdentifiableFile $file
     * @return UrlPatterns
     */
    protected function getUrlPatternsByFile(IdentifiableFile $file)
    {
        return $this->getUrlPatterns($file->getFileStorageName());
    }

    /**
     * @param string $storageName
     * @return UrlPatterns
     */
    public function getUrlPatterns($storageName)
    {
        if (!isset($this->urlPatterns[$storageName])) {
            throw new \InvalidArgumentException("Url Patterns for storage [$storageName] not found!");
        }

        return $this->urlPatterns[$storageName];
    }

    /**
     * @param string $search
     * @param string $replace
     * @param string $pattern
     * @return string
     */
    protected function substituteInPattern($search, $replace, $pattern)
    {
        if (preg_match('/\\' . $search . '(\((\w{1,})\)){0,1}/', $pattern, $matches) === 1) {
            if ($replace === null && isset($matches[2])) {
                $replace = $matches[2];
            }

            return str_replace($matches[0], $replace, $pattern);
        } else {
            throw new \UnexpectedValueException("Cannot replace [$search] by [$replace]! String [$pattern] does not contain [$search].");
        }
    }
}
