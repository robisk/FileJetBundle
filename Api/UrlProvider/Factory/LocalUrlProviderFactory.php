<?php

namespace Everlution\FileJetBundle\Api\UrlProvider\Factory;

use Doctrine\Common\Cache\Cache;
use Everlution\FileJetBundle\Api\UrlProvider;
use Everlution\FileJetBundle\Api\UrlProvider\LocalUrlProvider as Provider;
use Everlution\FileJetBundle\Api\UrlProvider\Patterns\UrlPatterns;
use Everlution\FileJetBundle\Storage\Storage;
use Everlution\FileJetBundle\Storage\Storages;

class LocalUrlProviderFactory
{
    const CACHE_KEY = 'everlution.file.url_patterns';

    /** @var Cache */
    protected $cache;

    /** @var UrlProvider */
    protected $urlProvider;

    /** @var Storages */
    protected $storages;

    /**
     * @param Cache $cache
     * @param UrlProvider $urlProvider
     * @param Storages $storages
     */
    public function __construct(Cache $cache, UrlProvider $urlProvider, Storages $storages)
    {
        $this->cache = $cache;
        $this->urlProvider = $urlProvider;
        $this->storages = $storages;
    }

    /**
     * @return Provider
     */
    public function create()
    {
        if ($this->cache->contains(static::CACHE_KEY)) {
            $patterns = $this->cache->fetch(static::CACHE_KEY);
        } else {
            $patterns = $this->toUrlPatterns($this->storages->getAll());
            $this->cache->save(static::CACHE_KEY, $patterns);
        }

        return new Provider($patterns);
    }

    /**
     * @param Storage[] $storages
     * @return UrlPatterns[]
     */
    protected function toUrlPatterns(array $storages)
    {
        $patterns = [];
        foreach ($storages as $storage) {
            $storageName = $storage->getName();
            $patterns[$storageName] = $this->urlProvider->getUrlPatterns($storageName);
        }
        return $patterns;
    }
}
