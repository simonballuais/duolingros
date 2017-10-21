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
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Exercise", inversedBy="complaints", cascade={"persist"})
     * @ORM\JoinColumn(name="exercise_text", referencedColumnName="text")
     */
    protected $exercise;

    /**
     * @ORM\Column(type="string", length=400)
     */
    protected $propositionText;

    public function __construct($exercise, $propositionText)
    {
        $this->exercise = $exercise;
        $this->propositionText = $propositionText;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getExercise()
    {
        return $this->exercise;
    }

    public function setExercise($exercise)
    {
        $this->exercise = $exercise;

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
}

