<?php

namespace Everlution\FileJetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait MutableFileTrait
{
    /**
     * @var string
     * @ORM\Column(name="file_identifier", type="string")
     */
    protected $fileIdentifier;

    /**
     * @var string
     * @ORM\Column(name="file_storage_name", type="string")
     */
    protected $fileStorageName;

    /**
     * @var string
     * @ORM\Column(name="file_mutation", type="string", nullable=true)
     */
    protected $fileMutation;

    /**
     * @param string $identifier
     * @param string $fileStorageName
     * @param string|null $mutation
     * @return $this
     */
    public function setFile($identifier, $fileStorageName, $mutation = null)
    {
        $this->fileIdentifier = $identifier;
        $this->fileStorageName = $fileStorageName;
        $this->fileMutation = $mutation;
        return $this;
    }

    /**
     * @param string $mutation
     * @return $this
     */
    public function setFileMutation($mutation)
    {
        $this->fileMutation = $mutation;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileIdentifier()
    {
        return $this->fileIdentifier;
    }

    /**
     * @return string
     */
    public function getFileStorageName()
    {
        return $this->fileStorageName;
    }

    /**
     * @return string|null
     */
    public function getFileMutation()
    {
        return $this->fileMutation;
    }
}
