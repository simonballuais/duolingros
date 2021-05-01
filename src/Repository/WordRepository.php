<?php
namespace App\Repository;

use DateTime;
use App\Entity\User;
use App\Entity\LearningSession;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class WordRepository extends EntityRepository
{
    public function findAllOriginalsSortedByLength()
    {
        return array_column(
            $this->getEntityManager()
                ->createQuery('
                    select w.original
                    from App\Entity\Word w
                    order by length(w.original) DESC
                ')
                ->getScalarResult(),
                'original'
            )
        ;
    }
}
