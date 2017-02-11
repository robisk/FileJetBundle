<?php

namespace Everlution\FileJetBundle\Entity;

interface MutableFile extends File
{
    /**
     * @param string $identifier
     * @param string $storageName
     * @param string|null $mutation
     * @return $this
     */
    public function setFile($identifier, $storageName, $mutation = null);

    /**
     * @param string $mutation
     * @return $this
     */
    public function setFileMutation($mutation);
}
