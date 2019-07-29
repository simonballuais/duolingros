<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="question_proposition")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Proposition
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
     * @ORM\Column(type="string", length=128)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("text")
     */
    protected $text;

    /**
     * @ORM\Column(type="string", length=16000)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("image")
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="propositionList", cascade={"persist"})
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    protected $question;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="answer", cascade={"persist"})
     */
    protected $rightAnswerFor;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $rightAnswer;

    public function __construct()
    {
        $this->rightAnswerFor = new ArrayCollection();
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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    public function isRightAnswer()
    {
        return $this->rightAnswer;
    }

    public function setRightAnswer($rightAnswer = true)
    {
        $this->rightAnswer = $rightAnswer;

        return $this;
    }
}
