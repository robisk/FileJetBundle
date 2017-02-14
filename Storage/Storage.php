<?php

namespace Everlution\FileJetBundle\Storage;

class Storage
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $apiKey;

    /** @var string */
    protected $name;

    /** @var string */
    protected $prefix;

    /**
     * @param string $id
     * @param string $apiKey
     * @param string $name
     * @param string $prefix
     */
    public function __construct($id, $apiKey, $name, $prefix)
    {
        $this->id = $id;
        $this->apiKey = $apiKey;
        $this->name = $name;
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return 'https://sm.filejet.io/storage/v1';
    }
}
