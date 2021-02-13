<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class LearningSessionRepository extends EntityRepository
{
    public function findCountForUserInTimespan($user, $start, $end)
    {
        $result = $this->getEntityManager()
            ->createQuery('
                select count(ls.id)
                from App\Entity\Learningsession ls
                where ls.user = :user
                    and ls.startedAt >= :start
                    and ls.startedAt <= :end
            ')
            ->setParameters([
                'user' => $user,
                'start' => $start,
                'end' => $end,
            ])
            ->getSingleScalarResult()
        ;

        return (int)$result;
    }
}

