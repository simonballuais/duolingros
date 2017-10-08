<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use AppBundle\Entity\Learning;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;

class LearningManager
{
    const SERVICE_NAME = 'app.learning_manager';
    const GOOD_PERCENTAGE = 75;

    protected $entityManager;
    protected $session;
    protected $repo;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repo = $entityManager->getRepository("AppBundle:Learning");
    }

    public function finishLesson(User $user, Lesson $lesson, $successPercentage)
    {
        $learning = $this->repo->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if (null === $learning) {
            $learning = new Learning();
            $learning->setUser($user);
            $learning->setLesson($lesson);

            $this->entityManager->persist($learning);
        }

        $lastPractice = $learning->getLastPractice();
        $now = new \DateTime();

        if ($now->format('d/m/Y') != $lastPractice->format('d/m/Y')) {
            if ($successPercentage >= self::GOOD_PERCENTAGE) {
                $learning->increaseGoodStreak();
                $learning->increaseVacationDays();
            }
            else {
                $learning->resetGoodStreak(0);
                $learning->decreaseVacationDays();
            }
        }

        $learning->setLastPractice($now);
        $learning->recordScore($successPercentage);

        return $learning;
    }
}
