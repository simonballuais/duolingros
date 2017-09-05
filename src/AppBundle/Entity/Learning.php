<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\AccessorOrder;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="learning")
 * @ExclusionPolicy("all")
 */
class Learning
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="learnings", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="learnings", cascade={"persist"})
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     */
    protected $lesson;

    /**
     * @ORM\Column(type="integer")
     */
    protected $goodStreak;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $lastPractice;

    /**
     * @ORM\Column(type="integer")
     */
    protected $vacationDays;

    /**
     * @ORM\Column(type="array")
     */
    protected $lastScores;

    public function __construct()
    {
        $this->goodStreak = 0;
        $this->vacationDays = -1;
        $this->lastPractice = new \DateTime();
        $this->lastPractice->modify('-1 day');
    }

     /**
      * @Groups({"Default"})
      * @SerializedName("mastery")
      * @VirtualProperty
      */
    public function getMastery()
    {
        $lastScores = $this->getLastScores();

        if (!$lastScores) {
            return null;
        }

        $mastery = array_sum($lastScores) / count($lastScores);
        $mastery = intval($mastery);

        return $mastery;
    }

    public function getHotness()
    {
        $now = new \DateTime();

        if ($now->getTimestamp() < $this->getNextPractice()->getTimestamp()) {
            return 3;
        }

        $diff = $this->getLastPractice()->diff($this->getNextPractice());
        $learningPeriod = $diff->days;

        $diff = $now->diff($this->getNextPractice());
        $actualLateness = $diff->days;

        $latenessScore = $actualLateness / $learningPeriod;
        $hotness = 3 - $latenessScore;
        $hotness = floor($hotness);

        if ($hotness < 0) {
            $hotness = 0;
        }

        $hotness = intval($hotness);

        return $hotness;
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

    public function getGoodStreak()
    {
        return $this->goodStreak;
    }

    public function setGoodStreak($goodStreak)
    {
        $this->goodStreak = $goodStreak;

        return $this;
    }

    public function getLastPractice()
    {
        return $this->lastPractice;
    }

    public function setLastPractice($lastPractice)
    {
        $this->lastPractice = $lastPractice;

        return $this;
    }

    public function getNextPractice()
    {
        $nextPractice = clone $this->lastPractice;
        $nextPractice->modify('+1 day');
        $nextPractice->modify('+' . $this->vacationDays . 'day');

        return $nextPractice;
    }

    public function setNextPractice($nextPractice)
    {
        $this->nextPractice = $nextPractice;

        return $this;
    }

    public function getVacationDays()
    {
        return $this->vacationDays;
    }

    public function setVacationDays($vacationDays)
    {
        $this->vacationDays = $vacationDays;

        if ($this->vacationDays <= -1) {
            $this->vacationDays = -1;
        }

        return $this;
    }

    public function increaseGoodStreak()
    {
        $this->goodStreak += 1;
        return $this;
    }

    public function resetGoodStreak()
    {
        $this->goodStreak = 0;
        return $this;
    }

    public function increaseVacationDays()
    {
        $this->vacationDays += 1;
        return $this;
    }

    public function decreaseVacationDays()
    {
        $this->vacationDays -= 1;
        return $this;
    }

    public function getLastScore()
    {
        if (!$this->lastScores) {
            return null;
        }

        return $this->lastScores[count($this->lastScores) - 1];
    }

    public function recordScore($score)
    {
        if (count($this->lastScores) >= 3) {
            array_shift($this->lastScores);
        }

        $this->lastScores[] = $score;
    }
    public function getLastScores()
    {
        return $this->lastScores;
    }
}
