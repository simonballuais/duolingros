<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Manager\BaseManager;
use App\Entity\Lesson;

class LessonManager
{
    protected $em;
    protected $session;
    protected $repo;

    protected $currentLessonId;
    protected $currentTranslationPlayed;
    protected $translationToPlay;

    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session
    ) {
        $this->em = $em;
        $this->session = $session;
        $this->repo = $em->getRepository("App:Lesson");
    }

    public function getAll()
    {
        return $this->repo->findAll();
    }

    public function getAllWithCurrentLearning($user)
    {
        $lessonList = $this->getAll();

        foreach ($lessonList as $lesson) {
            $learning = $user->getLearningForLesson($lesson);
            $lesson->setCurrentLearning($learning);
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

        foreach ($form->get('questions') as $questionForm) {
            $question = $questionForm->getData();
            $this->em->persist($question);
            $question->setLesson($lesson);

            foreach ($questionForm->get('propositions') as $propositionForm) {
                $proposition = $propositionForm->getData();

                if ($proposition->isRightAnswer()) {
                    $question->setAnswer($proposition);
                }

                $this->em->persist($proposition);
                $proposition->setQuestion($question);
            }
        }

        foreach ($form->get('translations') as $translationForm) {
            $translation = $translationForm->getData();
            $this->em->persist($translation);
            $translation->setLesson($lesson);
        }

        $this->em->flush();
    }

    public function createOrUpdate($title, $bookLessonId, $id="")
    {
        $bookLessonRepo = $this->em->getRepository('App:BookLesson');
        $bookLesson = $bookLessonRepo->findOneById($bookLessonId);

        $lesson = new Lesson();
        $lesson->setTitle($title);
        $lesson->setBookLesson($bookLesson);

        if ("" !== $id) {
            $lesson->setId($id);
            $this->em->persist($lesson);
            $metadata = $this->em->getClassMetaData(get_class($lesson));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetaData::GENERATOR_TYPE_NONE);
        }
        else {
            $this->em->merge($lesson);
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
