<?php

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\LearningSession;

use App\Model\PropositionInterface;
use App\Model\Proposition;
use App\Model\Exercise;
use App\Entity\Translation;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\Lesson;
use App\Manager\LessonManager;
use App\Manager\TranslationManager;
use App\Manager\QuestionManager;
use App\Manager\CorrectionManager;

class LearningSessionManager
{
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function start(User $user, Lesson $lesson, $difficulty)
    {
        $ls = new LearningSession();
        $ls->setUser($user);
        $ls->setLesson($lesson);
        $ls->setDifficulty($difficulty);
        $this->em->persist($ls);
        $this->em->flush();

        return $ls;
    }

    public function submit(LearningSession $ls)
    {
        $ls->setStatus(LearningSession::STATUS_SUBMITTED);

        $this->em->flush();
    }
}
