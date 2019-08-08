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
     * @ORM\Column(type="array")
     */
    protected $lastScores;

    public function __construct()
    {
        $this->goodStreak = 0;
        $this->lastPractice = new \DateTime();
        $this->lastPractice->modify('-1 day');
        $this->lastScores = [];
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

     /**
      * @Groups({"Default"})
      * @SerializedName("hotness")
      * @VirtualProperty
      */
    public function getHotness()
    {
        $now = new \DateTime();

        $vacationDays = $this->getVacationDays();

        $diff = $now->diff($this->getLastPractice());
        $lateness = $diff->days;

        $hotness = intval(round((($vacationDays * 3) - $lateness) / $vacationDays));

        if ($hotness < 1) {
            return 1;
        }
        if ($hotness > 3) {
            return 3;
        }

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

    public function getVacationDays()
    {
        $mastery = $this->getMastery();

        $days = 1;

        if ($mastery > 50) {
            $days = 2;
        }

        if ($mastery > 80) {
            $days = 9;
    }

        return $days;
    }

    public function getNextPractice()
    {
        $nextPractice = clone $this->lastPractice;
        $vacationDays = $this->getVacationDays();
        $nextPractice->modify('+' . $vacationDays .' day');

        return $nextPractice;
    }

    public function setNextPractice($nextPractice)
    {
        $this->nextPractice = $nextPractice;

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
     public function setLastScores($lastScores)
    {
        $this->lastScores = $lastScores;

        return $this;
    }
}
