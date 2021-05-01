<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\WordRepository;

/**
* @ORM\Entity(repositoryClass=WordRepository::class)
* @ORM\Table(name="word", indexes={@ORM\Index(name="original_idx", columns={"original"})})
*/
class Word
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=true)
     */
    protected $original;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $translations;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getKey()
    {
        return $this->original;
    }

    public function setKey($key)
    {
        $this->original = $key;

        return $this;
    }

    public function getValue()
    {
        return $this->translations;
    }

    public function setValue($value)
    {
        $this->translations = $value;

        return $this;
    }
}
