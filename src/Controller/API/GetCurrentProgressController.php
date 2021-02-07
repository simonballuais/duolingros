<?php

namespace App\Controller\API;

use App\Entity\Progress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class GetCurrentProgressController
{
    protected $em;
    protected $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function __invoke()
    {
        $progress = $this->em->getRepository(Progress::class)->findBy(
            ['user' => $this->security->getUser()]
        );

        return $progress;
    }
}
