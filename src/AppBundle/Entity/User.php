<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use AppBundle\Entity\Learning;


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
    protected $learningList;

    /**
     * @ORM\OneToMany(targetEntity="UnlockedLesson", mappedBy="user", cascade={"persist"})
     */
    protected $unlockedLessons;

    public function __construct()
    {
        parent::__construct();
        $this->learningList = new ArrayCollection();
        $this->unlockedLessons = new ArrayCollection();
    }

    public function getLearningList()
    {
        return $this->learningList;
    }

    public function setLearningList($learningList)
    {
        $this->learningList = $learningList;

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
        foreach ($this->learningList as $learning) {
            $lesson = $learning->getLesson();

            if ($learning->getLesson()->getId() === $lesson->getId()) {
                return $learning;
            }
        }

        return null;
    }
}
