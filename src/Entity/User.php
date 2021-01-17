<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\Learning;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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

    public function __construct()
    {
        parent::__construct();
        $this->learnings = new ArrayCollection();
        $this->unlockedLessons = new ArrayCollection();
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
}
