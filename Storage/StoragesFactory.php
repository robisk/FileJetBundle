<?php

namespace Everlution\FileJetBundle\Storage;

class StoragesFactory
{
    /**
     * @param array $storagesConfig
     * @return Storages
     */
    public static function createStorages(array $storagesConfig)
    {
        $storages = array_map(function ($config) {
            return new Storage($config['id'], $config['api_key'], $config['name'], $config['prefix']);
        }, $storagesConfig);

        return new Storages($storages);
    }
}
