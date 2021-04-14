<?php
namespace App\Repository;

use App\Entity\BookLesson;
use App\Entity\Lesson;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class LessonRepository extends EntityRepository
{
    public function getNextLessonOrderForBookLesson(BookLesson $bookLesson)
    {
        $result = $this->getEntityManager()
            ->createQuery('select max(l.order) from App\Entity\Lesson l where l.bookLesson = :bookLesson')
            ->setParameter('bookLesson', $bookLesson)
            ->getResult()
        ;

        if (isset($result[0]) && isset($result[0][1])) {
            return $result[0][1] + 1;
        }

        return 1;
    }

    public function getNextLessonAfter(Lesson $lesson): Lesson
    {
        $currentOrder = $lesson->getOrder();

        $nextLesson = $this->getEntityManager()
            ->createQuery('
                select l from App\Entity\Lesson l
                where l.bookLesson = :bookLesson
                    and l.order > :order
                order by l.order ASC
            ')
            ->setParameter('bookLesson', $lesson->getBookLesson())
            ->setParameter('order', $currentOrder)
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;

        if (!$nextLesson) {
            $nextLesson = $this->getFirstLessonOfBookLesson($lesson->getBookLesson());
        }

        return $nextLesson;
    }

    public function getFirstLessonOfBookLesson(BookLesson $bookLesson): Lesson
    {
        return $this->getEntityManager()
            ->createQuery('
                select l from App\Entity\Lesson l
                where l.bookLesson = :bookLesson
                order by l.order ASC
            ')
            ->setParameter('bookLesson', $bookLesson)
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    public function getLastLessonOfBookLesson(BookLesson $bookLesson): Lesson
    {
        return $this->getEntityManager()
            ->createQuery('
                select l from App\Entity\Lesson l
                where l.bookLesson = :bookLesson
                order by l.order DESC
            ')
            ->setParameter('bookLesson', $bookLesson)
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    public function findOneById($id): Lesson
    {
        $lesson = $this->getEntityManager()->createQuery('
            select l, t from App\Entity\Lesson l
            join l.translations t
            where l.id = :id
        ')
            ->setParameter('id', $id)
            ->getOneOrNullResult()
        ;

        $this->getEntityManager()->createQuery('
            select l, q, p from App\Entity\Lesson l
            join l.questions q
            join q.propositions p
            where l.id = :id
        ')
            ->setParameter('id', $id)
            ->getOneOrNullResult()
        ;

        return $lesson;
    }
}

