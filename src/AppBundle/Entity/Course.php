<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="course")
 */
class Course
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
     * @ORM\OneToMany(targetEntity="BookLesson", mappedBy="course", cascade={"persist"})
     */
    protected $bookLessonList;

    public function __construct()
    {
        $this->bookLessonList = new ArrayCollection();
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

    public function getBookLessonList()
    {
        return $this->bookLessonList;
    }

    public function setBookLessonList($bookLessonList)
    {
        $this->bookLessonList = $bookLessonList;

        return $this;
    }
}
