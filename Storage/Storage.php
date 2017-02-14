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

    /**
     * @param string $id
     * @param string $apiKey
     * @param string $name
     */
    public function __construct($id, $apiKey, $name)
    {
        $this->id = $id;
        $this->apiKey = $apiKey;
        $this->name = $name;
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
    public function getApiUrl()
    {
        return 'https://sm.filejet.io/storage/v1';
    }
}
