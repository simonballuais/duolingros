<?php

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

use App\Manager\BaseManager;
use App\Entity\Learning;
use App\Entity\Lesson;
use App\Entity\User;
use App\Entity\UnlockedLesson;

class LearningManager
{
    const GOOD_PERCENTAGE = 75;

    protected $em;
    protected $session;
    protected $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository("App:Learning");
    }

    public function finishLesson(User $user, Lesson $lesson, $successPercentage)
    {
        $learning = $this->repo->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if (null === $learning) {
            $learning = new Learning();
            $learning->setUser($user);
            $learning->setLesson($lesson);

            $this->em->persist($learning);
        }

        $this->unlockNextLessonIfNeeded($user, $lesson);

        $lastPractice = $learning->getLastPractice();
        $now = new \DateTime();

        if ($successPercentage >= self::GOOD_PERCENTAGE) {
            $learning->increaseGoodStreak();
            $learning->increaseMaxDifficulty();
        } else {
            if ($now->format('d/m/Y') != $lastPractice->format('d/m/Y')) {
                $learning->resetGoodStreak(0);
            }
        }

        $learning->setLastPractice($now);
        $learning->recordScore($successPercentage);

        return $learning;
    }

    public function unlockNextLessonIfNeeded(User $user, Lesson $lesson)
    {
        foreach ($lesson->getChildrenLessons() as $child) {
            if (!$child->isUnlockedForUser($user)) {
                $unlockedLesson = new UnlockedLesson();
                $unlockedLesson->setUser($user);
                $unlockedLesson->setLesson($child);
                $this->em->persist($unlockedLesson);
            }
        }

        $this->em->flush();
    }
}
