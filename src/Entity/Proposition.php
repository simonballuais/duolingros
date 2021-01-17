<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

use ApiPlatform\Core\Annotation as API;

/**
 * @ORM\Entity
 * @ORM\Table(name="question_proposition")
 *
 * @API\ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"securit"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_USER')"},
 *          "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_USER')"},
 *          "put"={"security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
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
     *
     * @Groups({"read", "writeLesson", "readItem"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("text")
     *
     * @Groups({"read", "writeLesson", "readItem"})
     */
    protected $text;

    /**
     * @ORM\Column(type="string", length=20000, nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("image")
     *
     * @Groups({"read", "readItem", "writeLesson"})
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="propositionList", cascade={"persist"})
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     */
    protected $question;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="answer", cascade={"persist"})
     */
    protected $rightAnswerFor;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read", "writeLesson", "readItem"})
     */
    protected $rightAnswer;

    public function __construct()
    {
        $this->rightAnswerFor = new ArrayCollection();
        $this->rightAnswer = false;
        $this->text = '';
        $this->image = '';
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
