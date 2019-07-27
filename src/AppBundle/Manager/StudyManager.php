<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Model\PropositionInterface;
use AppBundle\Model\Proposition;


class StudyManager
{
    const SERVICE_NAME = 'app.study_manager';

    protected $currentLesson;
    protected $currentLessonId;
    protected $currentTranslationText;
    protected $targetAmountPlayed;
    protected $currentAmountPlayed;
    protected $currentAmountSucceeded;

    protected $session;
    protected $entityManager;
    protected $lessonManager;
    protected $translationManager;

    public function __construct($entityManager, $session, $lessonManager, $translationManager)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->lessonManager = $lessonManager;
        $this->translationManager = $translationManager;

        $session->start();
    }

    public function startStudy($lesson)
    {
        $this->setCurrentLessonId($lesson->getId());
        $this->setCurrentAmountPlayed(0);
        $this->setCurrentAmountSucceeded(0);
        $this->setTargetAmountPlayed($lesson->getTranslationPerStudy());
        $translation = $this->getNextExercise();
        $this->setCurrentTranslationText($translation->getText());

        return $translation;
    }

    public function tryProposition(PropositionInterface $proposition)
    {
        $translation = $this->translationManager->get($this->getCurrentTranslationText());
        $correction = $translation->treatProposition($proposition);
        $this->setLastSubmittedProposition($proposition);

        if ($correction->isOk()) {
            $this->setCurrentAmountSucceeded($this->getCurrentAmountSucceeded() + 1);
            $this->setLastSolvedTranslationId($translation->getId());
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
        $translation = $lesson->getRandomTranslation($this->getLastSolvedTranslationId());
        $this->setCurrentTranslationText($translation->getText());

        return $translation;
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

    public function getCurrentTranslationText()
    {
        return $this->session->get('current_translation_text');
    }

    public function setCurrentTranslationText($currentTranslationText)
    {
        $this->session->set('current_translation_text', $currentTranslationText);

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

    public function setLastSubmittedProposition($proposition)
    {
        $this->session->set('submitted_proposition', $proposition->getText());

        return $this;
    }

    public function getLastSubmittedProposition()
    {
        $sessionProposition = $this->session->get('submitted_proposition');

        if (!$sessionProposition) {
            return null;
        }

        $proposition = new Proposition($sessionProposition);

        return $proposition;
    }

    public function getLastSolvedTranslationId()
    {
        return $this->session->get('last_solved_translation_id');
    }

    public function setLastSolvedTranslationId($id)
    {
        $this->session->set('last_solved_translation_id', $id);

        return $this;
    }
}
