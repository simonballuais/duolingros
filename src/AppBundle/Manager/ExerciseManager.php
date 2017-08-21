<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;

use AppBundle\Services\Mailer;
use AppBundle\Entity\Exercise;

class ExerciseManager
{
    const SERVICE_NAME = 'app.exercise_manager';

    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createOrUpdate($text, $answerList, $lesson)
    {
        $exercise = new Exercise();
        $exercise->setText($text);
        $exercise->setAnswerList($answerList);
        $exercise->setLesson($lesson);

        $this->entityManager->merge($exercise);

        return $exercise;
    }

    public function get($text)
    {
        $repoExercise = $this->entityManager->getRepository("AppBundle:Exercise");
        $exercise = $repoExercise->findOneBy(['text' => $text]);

        return $exercise;
    }

    public function getAll()
    {
        $repoExercise = $this->entityManager->getRepository("AppBundle:Exercise");
        $exercises = $repoExercise->findAll();

        return $exercises;
    }
}
