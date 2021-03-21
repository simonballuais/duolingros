<?php
namespace App\Repository;

use App\Entity\Course;
use App\Entity\Lesson;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class CourseRepository extends EntityRepository
{
    public function getCourseAfter(Course $course): ?Course
    {
        $currentOrder = $course->getOrder();

        $nextCourse = $this->getEntityManager()
            ->createQuery('
                select c from App\Entity\Course c
                where c.order > :order
                order by c.order ASC
            ')
            ->setParameter('order', $currentOrder)
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;

        return $nextCourse;
    }
}
