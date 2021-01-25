<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation as API;

/**
 * @ORM\Entity
 * @ORM\Table(name="course")
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
 *          }
 *     }
 * )
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
