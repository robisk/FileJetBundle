<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Everlution\FileJetBundle\Entity\MutableFile;
use Everlution\FileJetBundle\Entity\MutableFileTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="test_file")
 */

class TestFile implements MutableFile
{
    use MutableFileTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
