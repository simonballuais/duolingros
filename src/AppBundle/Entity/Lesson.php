<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\AccessorOrder;


/**
 * @ORM\Entity
 * @ORM\Table(name="lesson")
 * @ExclusionPolicy("all")
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
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $description;

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

    /**
     * @ORM\ManyToOne(targetEntity="BookLesson", inversedBy="lessons", cascade={"persist"})
     * @ORM\JoinColumn(name="book_lesson_id", referencedColumnName="id")
     */
    protected $bookLesson;

    protected $currentLearning;

    public function __construct()
    {
        $this->exerciseList = new ArrayCollection();
        $this->learningList = new ArrayCollection();
        $this->exercisePerStudy = 3;
    }

     /**
      * @Groups({"Default"})
      * @SerializedName("title")
      * @VirtualProperty
      */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

     /**
      * @Groups({"Default"})
      * @SerializedName("id")
      * @VirtualProperty
      */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    public function getRandomExercise($except = null)
    {
        if (count($this->exerciseList) === 1) {
            return $this->exerciseList[0];
        }

        do {
            $exercise = $this->pickRandomExercise();
        } while ($exercise->getId() === $except);

        return $exercise;
    }

    public function pickRandomExercise()
    {
        return $this->exerciseList[array_rand($this->exerciseList->toArray())];
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

     /**
      * @Groups({"Default"})
      * @SerializedName("currentLearning")
      * @VirtualProperty
      */
    public function getCurrentLearning()
    {
        return $this->currentLearning;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function setBookLesson($bookLesson)
    {
        $this->bookLesson = $bookLesson;

        return $this;
    }

    public function getBookLesson()
    {
        return $this->bookLesson;
    }
}
