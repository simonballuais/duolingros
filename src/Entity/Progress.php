<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation as API;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\API\GetCurrentProgressController;

/**
 * @ORM\Entity
 * @ORM\Table(name="progress")
 *
 * @API\ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"securit"="is_granted('ROLE_ADMIN')"},
 *     collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"progress.readCollection"}}
 *          },
 *          "get_current_progress"={
 *              "security"="is_granted('ROLE_USER')",
 *              "normalization_context"={"groups"={"progress.readCollection"}},
 *              "controller"=GetCurrentProgressController::class,
 *              "method"="GET",
 *              "path"="/current-progress.{_format}"
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"progress.readItem"}}
 *          },
 *     }
 * )
 */
class Progress
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"progress.readItem", "progress.readCollection"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="progresses", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson")
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     */
    protected $lesson;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"progress.readItem", "progress.readCollection"})
     */
    protected $difficulty;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"progress.readItem", "progress.readCollection"})
     */
    protected $completed;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"progress.readItem", "progress.readCollection"})
     */
    protected $cycleProgression;

    /**
     * @ORM\ManyToOne(targetEntity="BookLesson")
     * @ORM\JoinColumn(name="book_lesson_id", referencedColumnName="id")
     */
    protected $bookLesson;

    public function __construct()
    {
        $this->completed = false;
        $this->cycleProgression = 0;
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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

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

    /**
     * @Groups({"progress.readItem", "progress.readCollection"})
     */
    public function getCurrentLessonId()
    {
        if (!$this->lesson) {
            return null;
        }

        return $this->lesson->getId();
    }

    /**
     * @Groups({"progress.readItem", "progress.readCollection"})
     */
    public function getTotalLessonCount()
    {
        return $this->getBookLesson()->getLessonList()->count();
    }

    /**
     * @Groups({"progress.readItem", "progress.readCollection"})
     */
    public function getBookLessonId()
    {
        if (!$this->bookLesson) {
            return null;
        }

        return $this->bookLesson->getId();
    }

    public function getBookLesson()
    {
        return $this->bookLesson;
    }

    public function isUnlocked()
    {
        return true;
    }

    public function getDifficulty()
    {
        return $this->difficulty;
    }

    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function incrementDifficulty(): self
    {
        if ($this->difficulty < 5) {
            $this->difficulty++;
        }

        return self;
    }

    public function isCompleted()
    {
        return $this->completed;
    }

    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    public function getCycleProgression()
    {
        return $this->cycleProgression;
    }

    public function setCycleProgression($cycleProgression)
    {
        $this->cycleProgression = $cycleProgression;

        return $this;
    }

    public function incrementCycleProgression(): self
    {
        $this->cycleProgression++;

        return $this;
    }

    public function setBookLesson(BookLesson $bookLesson): self
    {
        $this->bookLesson = $bookLesson;

        return $this;
    }
}
