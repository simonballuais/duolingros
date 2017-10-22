<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use AppBundle\Entity\BookLesson;

class BookLessonManager
{
    const SERVICE_NAME = 'app.book_lesson_manager';

    protected $entityManager;
    protected $repo;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repo = $entityManager->getRepository("AppBundle:BookLesson");
    }

    public function createOrUpdate($title, $subtitle, $id="")
    {
        $bookLesson = new BookLesson();
        $bookLesson->setTitle($title);
        $bookLesson->setSubtitle($subtitle);

        if ("" !== $id) {
            $bookLesson->setId($id);
            $this->entityManager->persist($bookLesson);
            $metadata = $this->entityManager->getClassMetaData(get_class($bookLesson));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetaData::GENERATOR_TYPE_NONE);
        }
        else {
            $this->entityManager->merge($bookLesson);
        }

        return $bookLesson;
    }

    public function get($id)
    {
        $bookLesson = $this->repo->findOneById($id);

        return $bookLesson;
    }

    public function getAll()
    {
        $bookLessons = $this->repo->findAll();

        return $bookLessons;
    }

    public function getAllWithCurrentLearning($user)
    {
        $bookLessons = $this->getAll();
        $learningList = $user->getLearningList();

        foreach ($bookLessons as $bookLesson) {
            $lessonList = $bookLesson->getLessonList();

            foreach ($learningList as $learning) {
                foreach ($lessonList as $lesson) {
                    if ($learning->getLesson()->getId() === $lesson->getId()) {
                        $lesson->setCurrentLearning($learning);
                    }
                }
            }
        }

        return $bookLessons;
    }
}
