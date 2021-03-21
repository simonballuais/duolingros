<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Groups as JMSGroups;
use JMS\Serializer\Annotation\Expose;
use ApiPlatform\Core\Annotation as API;
use App\Repository\CourseRepository;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
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
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     * @Expose
     * @JMSGroups({"startLearningSession"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     * @Expose
     * @JMSGroups({"startLearningSession"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=200)
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     * @Expose
     * @JMSGroups({"startLearningSession"})
     */
    protected $subtitle;

    /**
     * @ORM\OneToMany(targetEntity="BookLesson", mappedBy="course", cascade={"persist"})
     * @ORM\OrderBy({"order": "ASC"})
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     * @Expose
     * @JMSGroups({"startLearningSession"})
     */
    protected $bookLessonList;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     * @Expose
     * @JMSGroups({"startLearningSession"})
     */
    protected $order;

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

    public function getFirstBookLesson(): ?BookLesson
    {
        return $this->bookLessonList->first();
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}
