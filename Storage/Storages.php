<?php

namespace Everlution\FileJetBundle\Storage;

class Storages
{
    /** @var Storage[] */
    protected $storages;

    /**
     * @param Storage[] $storages
     */
    public function __construct(array $storages)
    {
        $this->storages = $storages;
    }

    /**
     * @return Storage[]
     */
    public function getAll()
    {
        return $this->storages;
    }

    /**
     * @param string $name
     * @return Storage
     */
    public function getByName($name)
    {
        foreach ($this->storages as $storage) {
            if ($storage->getName() === $name) {
                return $storage;
            }
        }
        throw new \InvalidArgumentException("Storage with name [$name] not exist!");
    }
}
