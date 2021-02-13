<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class UserRepository extends EntityRepository
{
    public function findAllActiveIds()
    {
        $result = $this->getEntityManager()
            ->createQuery('select u.id from App\Entity\User u')
            ->getScalarResult()
        ;

        return array_column($result, 'id');
    }
}

