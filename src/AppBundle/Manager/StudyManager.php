<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Model\PropositionInterface;


class StudyManager
{
    const SERVICE_NAME = 'app.study_manager';

    protected $currentLesson;
    protected $currentLessonId;
    protected $currentExerciseText;
    protected $targetAmountPlayed;
    protected $currentAmountPlayed;
    protected $currentAmountSucceeded;

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
        $this->setCurrentLessonId($lesson->getId());
        $this->setCurrentAmountPlayed(0);
        $this->setCurrentAmountSucceeded(0);
        $this->setTargetAmountPlayed($lesson->getExercisePerStudy());
        $exercise = $this->getNextExercise();
        $this->setCurrentExerciseText($exercise->getText());

        return $exercise;
    }

    public function tryProposition(PropositionInterface $proposition)
    {
        $exercise = $this->exerciseManager->get($this->getCurrentExerciseText());
        $correction = $exercise->treatProposition($proposition);

        if ($correction->isOk()) {
            $this->setCurrentAmountSucceeded($this->getCurrentAmountSucceeded() + 1);
        }

        $this->setCurrentAmountPlayed($this->getCurrentAmountPlayed() + 1);

        return $correction;
    }

    public function isOver()
    {
        return $this->getCurrentAmountSucceeded() >= $this->getTargetAmountPlayed();
    }

    public function getNextExercise()
    {
        if ($this->isOver()) {
            return null;
        }

        $lesson = $this->getCurrentLesson();
        $exercise = $lesson->getRandomExercise();
        $this->setCurrentExerciseText($exercise->getText());

        return $exercise;
    }

    public function getMistakes()
    {
        return $this->getCurrentAmountPlayed() / $this->getCurrentAmountSucceeded();
    }

    public function getSuccessPercentage()
    {
        return number_format(
            $this->getCurrentAmountSucceeded() / $this->getCurrentAmountPlayed() * 100,
            2
        );
    }

    public function getProgress()
    {
        return $this->getCurrentAmountSucceeded() / $this->getTargetAmountPlayed() * 100;
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

    public function getCurrentAmountSucceeded()
    {
        return $this->session->get('current_amount_succeeded');
    }

    public function setCurrentAmountSucceeded($currentAmountSucceeded)
    {
        $this->session->set('current_amount_succeeded', $currentAmountSucceeded);

        return $this;
    }

    public function getCurrentLesson()
    {
        if ($this->currentLesson === null) {
            $this->currentLesson = $this->lessonManager->get($this->getCurrentLessonId());
        }

        return $this->currentLesson;
    }
}
