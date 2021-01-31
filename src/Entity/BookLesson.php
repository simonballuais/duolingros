<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation as API;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="book_lesson")
 *
 * @API\ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"securit"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')",
 *              "normalization_context"={"groups"={"readCollection"}}
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"writeCollection"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')",
 *              "normalization_context"={"groups"={"readItem"}}
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"writeItem"}}
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"deleteItem"}}
 *          }
 *     }
 * )
 */
class BookLesson
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write", "writeCollection"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=200)
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write", "writeCollection"})
     */
    protected $subtitle;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="bookLesson", cascade={"persist"})
     * @ORM\OrderBy({"order": "ASC"})<`0`>
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     */
    protected $lessonList;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="bookLessonList", cascade={"persist"})
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    protected $course;

    public function __construct()
    {
        $this->lessonList = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }


    public function getLessonList()
    {
        return $this->lessonList;
    }

    public function setLessonList($lessonList)
    {
        $this->lessonList = $lessonList;

        return $this;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }
}
