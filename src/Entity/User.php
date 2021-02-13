<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation as API;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\Learning;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 *
 * @API\ApiResource(
 *     normalizationContext={"groups"={"user.read"}},
 *     attributes={"securit"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"user.readCollection"}}
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *              "normalization_context"={"groups"={"user.readItem"}}
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *              "normalization_context"={"groups"={"user.writeItem"}}
 *          },
 *     }
 * )
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"user.readCollection", "user.readItem"})
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Learning", mappedBy="user", cascade={"persist"})
     */
    protected $learnings;

    /**
     * @ORM\OneToMany(targetEntity="UnlockedLesson", mappedBy="user", cascade={"persist"})
     */
    protected $unlockedLessons;

    /**
     * @ORM\OneToMany(targetEntity="Progress", mappedBy="user", cascade={"persist"})
     */
    protected $progresses;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"user.readCollection", "user.writeItem", "user.readItem"})
     */
    protected $dailyObjective;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"user.readCollection", "user.readItem"})
     */
    protected $learningSessionCountThatDay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"user.readCollection", "user.readItem"})
     */
    protected $currentSerie;

    public function __construct()
    {
        parent::__construct();
        $this->learnings = new ArrayCollection();
        $this->unlockedLessons = new ArrayCollection();
        $this->progresses = new ArrayCollection();
    }

    public function getLearnings()
    {
        return $this->learnings;
    }

    public function setLearnings($learnings)
    {
        $this->learnings = $learnings;

        return $this;
    }

    public function getUnlockedLessons()
    {
        return $this->unlockedLessons;
    }

    public function setUnlockedLessons($unlockedLessons)
    {
        $this->unlockedLessons = $unlockedLessons;

        return $this;
    }

    public function getLearningForLesson(Lesson $lesson)
    {
        foreach ($this->learnings as $learning) {
            $lesson = $learning->getLesson();

            if ($learning->getLesson()->getId() === $lesson->getId()) {
                return $learning;
            }
        }

        return null;
    }

    /**
     * @Groups({"security"})
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getProgresses()
    {
        return $this->progresses;
    }

    public function setProgresses($progresses)
    {
        $this->progresses = $progresses;

        return $this;
    }

    public function getDailyObjective()
    {
        return $this->dailyObjective;
    }

    public function setDailyObjective($dailyObjective)
    {
        $this->dailyObjective = $dailyObjective;

        return $this;
    }

    public function getLearningSessionCountThatDay()
    {
        return $this->learningSessionCountThatDay;
    }

    public function setLearningSessionCountThatDay($learningSessionCountThatDay)
    {
        $this->learningSessionCountThatDay = $learningSessionCountThatDay;

        return $this;
    }

    public function getCurrentSerie()
    {
        return $this->currentSerie;
    }

    public function setCurrentSerie($currentSerie)
    {
        $this->currentSerie = $currentSerie;

        return $this;
    }
}
