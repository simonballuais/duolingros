<?php
namespace App\Repository;

use DateTime;
use App\Entity\User;
use App\Entity\LearningSession;
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

    public function getLastSevenDaysCountsForUser($user): array
    {
        $sixDaysAgo = (new DateTime())->modify('-6 days')->setTime(0, 0, 0);

        $results = $this->getEntityManager()
            ->createQuery('
                select ls
                from App\Entity\Learningsession ls
                where ls.user = :user
                    and ls.startedAt >= :start
                    and ls.status = :accepted
            ')
            ->setParameters([
                'user' => $user,
                'start' => $sixDaysAgo,
                'accepted' => LearningSession::STATUS_ACCEPTED,
            ])
            ->getResult()
        ;

        $countedResults = [];
        $dateCursor = clone $sixDaysAgo;

        for ($i = 0; $i < 7; $i++)
        {
            $dateKey = $dateCursor->format('Y-m-d');
            $countedResults[$dateKey] = 0;
            $dateCursor->modify('+1 day');
        }

        foreach ($results as $result) {
            $dateKey = $result->getStartedAt()->format('Y-m-d');
            $countedResults[$dateKey]++;
        }

        return $countedResults;
    }
}

