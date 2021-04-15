<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation as API;
use Symfony\Component\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Groups as JMSGroups;
use JMS\Serializer\Annotation\Expose;
use App\Repository\BookLessonRepository;

/**
 t @ORM\Entity(repositoryClass=BookLessonRepository::class)
 * @ORM\Table(name="book_lesson")
 *
 * @API\ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"securit"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"readCollection"}}
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"writeCollection"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
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
     * @Expose
     * @JMSGroups({"startLearningSession"})
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
     * @ORM\OrderBy({"order": "ASC"})
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     * @Expose
     * @JMSGroups({"startLearningSession"})
     */
    protected $lessonList;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="bookLessonList", cascade={"persist"})
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    protected $course;

    /**
     * @ORM\Column(type="integer", name="order")
     *
     * @Groups({"readCollection", "writeItem", "readItem",  "write"})
     * @Expose
     * @JMSGroups({"startLearningSession"})
     */
    protected $order;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $disabled;

    protected $progress;

    public function __construct()
    {
        $this->lessonList = new ArrayCollection();
        $this->diabled = true;
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

    /**
     * @Groups({"readCollection", "readItem"})
     */
    public function getCourseId()
    {
        return $this->course->getId();
    }

    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    public function getProgress()
    {
        return $this->progress;
    }

    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
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

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }
}
