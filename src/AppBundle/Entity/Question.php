<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="question")
*/
class Question
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=225)
     */
    protected $text;

    /**
     * @ORM\OneToMany(targetEntity="Proposition", mappedBy="question", cascade={"persist"})
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

    public function __construct()
    {
        $this->propositionList = new ArrayCollection();
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
}
