<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

use AppBundle\Model\Exercise;

/**
* @ORM\Entity
* @ORM\Table(name="question")
*
* @Serializer\ExclusionPolicy("all")
*/
class Question implements Exercise
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("id")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=225)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("text")
     */
    protected $text;

    /**
     * @ORM\OneToMany(targetEntity="Proposition", mappedBy="question", cascade={"persist"})
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("propositionList")
     */
    protected $propositionList;

    /**
     * @ORM\ManyToOne(targetEntity="Proposition", inversedBy="rightAnswerFor", cascade={"persist"})
     * @ORM\JoinColumn(name="right_answer_for_id", referencedColumnName="id", nullable=true)
     */
    protected $answer;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="questionList", cascade={"persist"})
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     */
    protected $lesson;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("isNoPictures")
     */
    protected $noPictures;

    /**
     * @ORM\Column(type="integer", options={"default":1}, nullable=true)
     */
    protected $difficulty;

    public function __construct()
    {
        $this->propositionList = new ArrayCollection();
        $this->noPictures = false;
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

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function getLesson()
    {
        return $this->lesson;
    }

    public function setLesson($lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getPropositionList()
    {
        return $this->propositionList;
    }

    public function setPropositionList($propositionList)
    {
        $this->propositionList = $propositionList;

        return $this;
    }

    public function addProposition($proposition)
    {
        $this->propositionList[] = $proposition;
    }

    public function removeProposition($proposition)
    {
        $this->propositionList->removeElement($proposition);
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer($answer)
    {
        foreach ($this->propositionList as $proposition) {
            $proposition->setRightAnswer(false);
        }

        $answer->setRightAnswer();
        $this->answer = $answer;

        return $this;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("type")
     */
    public function getExerciseType()
    {
        return 'question';
    }

    public function isNoPictures()
    {
        return $this->noPictures;
    }

    public function setNoPictures($noPictures)
    {
        $this->noPictures = $noPictures;

        return $this;
    }

    public function getDifficulty(): int
    {
        if (!$this->difficulty) {
            return 1;
        }

        return $this->difficulty;
    }

    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }
}
