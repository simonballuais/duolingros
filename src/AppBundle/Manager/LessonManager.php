<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use AppBundle\Entity\Lesson;

class LessonManager
{
    const SERVICE_NAME = 'app.lesson_manager';

    protected $entityManager;
    protected $session;
    protected $repo;

    protected $currentLessonId;
    protected $currentTranslationPlayed;
    protected $translationToPlay;

    public function __construct($entityManager, $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->repo = $entityManager->getRepository("AppBundle:Lesson");
    }

    public function getAll()
    {
        return $this->repo->findAll();
    }

    public function getAllWithCurrentLearning($user)
    {
        $lessonList = $this->getAll();
        $learningList = $user->getLearningList();

        foreach ($learningList as $learning) {
            foreach ($lessonList as $lesson) {
                if ($learning->getLesson()->getId() === $lesson->getId()) {
                    $lesson->setCurrentLearning($learning);
                }
            }
        }

        return $lessonList;
    }

    public function get($id)
    {
        return $this->repo->findOneById($id);
    }

    public function update($form)
    {
        $lesson = $form->getData();

        foreach ($form->get('questionList') as $questionForm) {
            $question = $questionForm->getData();
            $this->entityManager->persist($question);
            $question->setLesson($lesson);

            foreach ($questionForm->get('propositionList') as $propositionForm) {
                $proposition = $propositionForm->getData();

                if ($proposition->isRightAnswer()) {
                    $question->setAnswer($proposition);
                }

                $this->entityManager->persist($proposition);
                $proposition->setQuestion($question);
            }
        }

        $this->entityManager->flush();
    }

    public function createOrUpdate($title, $bookLessonId, $id="")
    {
        $bookLessonRepo = $this->entityManager->getRepository('AppBundle:BookLesson');
        $bookLesson = $bookLessonRepo->findOneById($bookLessonId);

        $lesson = new Lesson();
        $lesson->setTitle($title);
        $lesson->setBookLesson($bookLesson);

        if ("" !== $id) {
            $lesson->setId($id);
            $this->entityManager->persist($lesson);
            $metadata = $this->entityManager->getClassMetaData(get_class($lesson));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetaData::GENERATOR_TYPE_NONE);
        }
        else {
            $this->entityManager->merge($lesson);
        }

        return $lesson;
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

    public function getCurrentTranslationPlayed()
    {
        return $this->currentTranslationPlayed;
    }

    public function setCurrentTranslationPlayed($currentTranslationPlayed)
    {
        $this->currentTranslationPlayed = $currentTranslationPlayed;

        return $this;
    }

    public function getTranslationToPlay()
    {
        return $this->translationToPlay;
    }

    public function setTranslationToPlay($translationToPlay)
    {
        $this->translationToPlay = $translationToPlay;

        return $this;
    }
}
