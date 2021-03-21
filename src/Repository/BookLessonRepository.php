<?php
namespace App\Repository;

use App\Entity\Course;
use App\Entity\BookLesson;
use App\Entity\Lesson;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class BookLessonRepository extends EntityRepository
{
    public function getNextBookLessonAfter(BookLesson $bookLesson): ?BookLesson
    {
        $currentOrder = $bookLesson->getOrder();

        $nextBookLesson = $this->getEntityManager()
            ->createQuery('
                select bl from App\Entity\BookLesson bl
                where bl.course = :course
                    and bl.order > :order
                order by bl.order ASC
            ')
            ->setParameter('course', $bookLesson->getCourse())
            ->setParameter('order', $currentOrder)
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;

        return $nextBookLesson;
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
}
