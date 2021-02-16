<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\AccessorOrder;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use App\Repository\LearningSessionRepository;


/**
 * @ORM\Entity(repositoryClass=LearningSessionRepository::class)
 * @ORM\Table(name="learning_session")
 * @ExclusionPolicy("all")
 */
class LearningSession
{
    const STATUS_STARTED = 'started';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_GAVE_UP = 'gave_up';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"startLearningSession"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="learnings", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="learnings", cascade={"persist"})
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     * @Expose
     * @Groups({"startLearningSession"})
     */
    protected $lesson;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     * @Groups({"startLearningSession"})
     */
    protected $difficulty;

    /**
     * @ORM\Column(type="string", length=10)
     * @Expose
     * @Groups({"startLearningSession"})
     */
    protected $status;

    /**
     * @ORM\Column(type="array")
     */
    protected $answers;

    /**
     * @ORM\Column(type="datetime")
     * @Expose
     * @Groups({"startLearningSession"})
     */
    protected $startedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     * @Groups({"startLearningSession"})
     */
    protected $submittedAt;

    /**
     * @Expose
     * @Groups({"startLearningSession"})
     */
    protected $lastLesson;

    public function __construct()
    {
        $this->status = self::STATUS_STARTED;
        $this->startedAt = new DateTime();
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

    public function getAnswers()
    {
        return $this->answers;
    }

    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }

    public function getStartedAt()
    {
        return $this->startedAt;
    }

    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
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

    /**
     * @VirtualProperty
     * @Groups({"startLearningSession"})
     */
    public function getTranslations()
    {
        return $this->lesson->getTranslationsOfDifficulty($this->difficulty);
    }

    /**
     * @VirtualProperty
     * @Groups({"startLearningSession"})
     */
    public function getQuestions()
    {
        return $this->lesson->getQuestionsOfDifficulty($this->difficulty);
    }

    public function getSubmittedAt()
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt($submittedAt)
    {
        $this->submittedAt = $submittedAt;

        return $this;
    }

    public function isStarted(): bool
    {
        return self::STATUS_STARTED === $this->status;
    }

    public function accept(): self
    {
        $this->status = self::STATUS_ACCEPTED;
        $this->setSubmittedAt(new DateTime());

        return $this;
    }

    public function getBookLesson()
    {
        return $this->lesson->getBookLesson();
    }

    public function getLastLesson()
    {
        return $this->lastLesson;
    }

    public function setLastLesson($lastLesson)
    {
        $this->lastLesson = $lastLesson;

        return $this;
    }
}
