<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="lesson")
 */
class Lesson
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="integer")
     */
    protected $exercisePerStudy;

    /**
     * @ORM\OneToMany(targetEntity="Exercise", mappedBy="lesson", cascade={"persist"})
     */
    protected $exerciseList;

    /**
     * @ORM\OneToMany(targetEntity="Learning", mappedBy="lesson", cascade={"persist"})
     */
    protected $learningList;

    protected $currentLearning;

    public function __construct()
    {
        $this->exerciseList = new ArrayCollection();
        $this->learningList = new ArrayCollection();
        $this->exercisePerStudy = 3;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    public function getRandomExercise()
    {
        $index = rand(0, count($this->exerciseList) - 1);

        return $this->exerciseList[$index];
    }
    public function __toString()
    {
        return sprintf("Lesson [%s] - %s", $this->id, $this->title);
    }

    public function getExercisePerStudy()
    {
        return $this->exercisePerStudy;
    }

    public function setExercisePerStudy($exercisePerStudy)
    {
        $this->exercisePerStudy = $exercisePerStudy;

        return $this;
    }

    public function getLearningList()
    {
        return $this->learningList;
    }

    public function setLearningList($learningList)
    {
        $this->learningList = $learningList;

        return $this;
    }

    public function setCurrentLearning($currentLearning)
    {
        $this->currentLearning = $currentLearning;

        return $this;
    }

    public function getCurrentLearning()
    {
        return $this->currentLearning;
    }
}
