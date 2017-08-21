<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Model\PropositionInterface;


class StudyManager
{
    const SERVICE_NAME = 'app.study_manager';

    protected $currentLessonId;
    protected $currentExerciseText;
    protected $targetAmountPlayed;
    protected $currentAmountPlayed;

    protected $session;
    protected $entityManager;
    protected $lessonManager;
    protected $exerciseManager;

    public function __construct($entityManager, $session, $lessonManager, $exerciseManager)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->lessonManager = $lessonManager;
        $this->exerciseManager = $exerciseManager;

        $session->start();
    }

    public function startStudy($lesson)
    {
        $exercise = $lesson->getRandomExercise();
        $this->setCurrentLessonId($lesson->getId());
        $this->setCurrentExerciseText($exercise->getText());
        $this->setCurrentAmountPlayed(0);
        $this->setTargetAmountPlayed(10);

        return $exercise;
    }

    public function tryProposition(PropositionInterface $proposition)
    {
        $exercise = $this->exerciseManager->get($this->getCurrentExerciseText());
        $correction = $exercise->treatProposition($proposition);

        if ($correction->isOk()) {
            $this->setCurrentAmountPlayed($this->getCurrentAmountPlayed() + 1);
        }

        return $correction;
    }

    public function getNextExercise()
    {
        $lesson = $this->lessonManager->get($this->getCurrentLessonId());

        return $lesson->getRandomExercise();
    }

    public function getProgress()
    {
        return $this->getCurrentAmountPlayed() / $this->getTargetAmountPlayed() * 100;
    }

    public function getCurrentLessonId()
    {
        return $this->session->get('current_lesson_id');
    }

    public function setCurrentLessonId($currentLessonId)
    {
        $this->session->set('current_lesson_id', $currentLessonId);

        return $this;
    }

    public function getCurrentExerciseText()
    {
        return $this->session->get('current_exercise_text');
    }

    public function setCurrentExerciseText($currentExerciseText)
    {
        $this->session->set('current_exercise_text', $currentExerciseText);

        return $this;
    }

    public function getTargetAmountPlayed()
    {
        return $this->session->get('target_amount_played');
    }

    public function setTargetAmountPlayed($targetAmountPlayed)
    {
        $this->session->set('target_amount_played', $targetAmountPlayed);

        return $this;
    }

    public function getCurrentAmountPlayed()
    {
        return $this->session->get('current_amount_played');
    }

    public function setCurrentAmountPlayed($currentAmountPlayed)
    {
        $this->session->set('current_amount_played', $currentAmountPlayed);

        return $this;
    }
}
