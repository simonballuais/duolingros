<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(User::class));
    }

    public function findAllActiveIds()
    {
        $result = $this->getEntityManager()
            ->createQuery('select u.id from App\Entity\User u')
            ->getScalarResult()
        ;

        return array_column($result, 'id');
    }
}

