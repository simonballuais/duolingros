<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="book_lesson")
 */
class BookLesson
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $subtitle;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="bookLesson", cascade={"persist"})
     * @ORM\OrderBy({"order": "ASC"})<`0`>
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
