<?php
namespace App\Repository;

use App\Entity\BookLesson;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class ProgressRepository extends EntityRepository
{
    public function hasUserCompletedWholeCourse(User $user, Course $course): bool
    {
        $todo = $this->getEntityManager()
            ->createQuery('
                select count(bl) from App\Entity\BookLesson bl
                where bl.course = :course
            ')
            ->setParameter('course', $course)
            ->setMaxResults(1)
            ->getSingleScalarResult()
        ;

        $done = $this->getEntityManager()
            ->createQuery('
                select count(bl) from App\Entity\Progress p
                join p.bookLesson bl
                where bl.course = :course
                    and p.completed = TRUE
                    and p.user = :user
            ')
            ->setParameter('course', $course)
            ->setParameter('user', $user)
            ->setMaxResults(1)
            ->getSingleScalarResult()
        ;

        return $done >= $todo;
    }
}

