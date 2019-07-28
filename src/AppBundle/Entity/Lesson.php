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
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="lesson", cascade={"persist"})
     */
    protected $translationList;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="lesson", cascade={"persist"})
     */
    protected $questionList;

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
        $this->translationList = new ArrayCollection();
        $this->questionList = new ArrayCollection();
        $this->learningList = new ArrayCollection();
        $this->translationPerStudy = 3;
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
        $pickables = array_merge(
            $this->translationList->toArray(),
            $this->questionList->toArray()
        );

        if (count($pickables) === 1) {
            return $pickables[0];
        }

        do {
            $result = $this->pickRandomExercise();
        } while ($result->getId() === $except);

        return $result;
    }

    public function pickRandomExercise()
    {
        $pickables = array_merge(
            $this->translationList->toArray(),
            $this->questionList->toArray()
        );

        return $pickables[array_rand($pickables)];
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

    public function getQuestionList()
    {
        return $this->questionList;
    }

    public function setQuestionList($questionList)
    {
        $this->questionList = $questionList;

        return $this;
    }

    public function addQuestion($question)
    {
        $this->questionList[] = $question;
    }

    public function removeQuestion($question)
    {
        $this->questionList->removeElement($question);
    }

    public function getTranslationList()
    {
        return $this->translationList;
    }

    public function setTranslationList($translationList)
    {
        $this->translationList = $translationList;

        return $this;
    }
}
