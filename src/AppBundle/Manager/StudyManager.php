<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use AppBundle\Services\Mailer;

class StudyManager
{
    const SERVICE_NAME = 'app.study_manager';

    protected $currentLessonId;
    protected $currentExercisePlayed;
    protected $exerciseToPlay;

    public function __construct($entityManager, $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function getCurrentLessonId()
    {
        return $this->currentLessonId;
    }

    public function setCurrentLessonId($currentLessonId)
    {
        $this->currentLessonId = $currentLessonId;

        return $this;
    }

    public function getCurrentExercisePlayed()
    {
        return $this->currentExercisePlayed;
    }

    public function setCurrentExercisePlayed($currentExercisePlayed)
    {
        $this->currentExercisePlayed = $currentExercisePlayed;

        return $this;
    }

    public function getExerciseToPlay()
    {
        return $this->exerciseToPlay;
    }

    public function setExerciseToPlay($exerciseToPlay)
    {
        $this->exerciseToPlay = $exerciseToPlay;

        return $this;
    }
}
