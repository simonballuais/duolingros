<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="complaint")
 */
class Complaint
{
    const IN_PROGRESS = "in progress";
    const CORRECTED = "corrected";
    const REFUSED = "refused";

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Translation", inversedBy="complaints", cascade={"persist"})
     * @ORM\JoinColumn(name="translation_id", referencedColumnName="id")
     */
    protected $translation;

    /**
     * @ORM\Column(type="string", length=400)
     */
    protected $propositionText;

    /**
        * @ORM\Column(type="string", length=15)
     */
    protected $status;

    public function __construct($translation, $propositionText)
    {
        $this->translation = $translation;
        $this->propositionText = $propositionText;
        $this->status = self::IN_PROGRESS;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTranslation()
    {
        return $this->translation;
    }

    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    public function getPropositionText()
    {
        return $this->propositionText;
    }

    public function setPropositionText($propositionText)
    {
        $this->propositionText = $propositionText;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function isInProgress()
    {
        return self::IN_PROGRESS === $this->status;
    }

    public function isCorrected()
    {
        return self::CORRECTED === $this->status;
    }

    public function isRefused()
    {
        return self::REFUSED === $this->status;
    }
}

