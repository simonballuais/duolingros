<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Manager\BaseManager;
use App\Entity\Complaint;

class ComplaintManager
{
    protected $entityManager;
    protected $session;
    protected $repo;

    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ) {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->repo = $entityManager->getRepository("App:Complaint");
    }

    public function addComplaint($translation, $propositionText)
    {
        if (!$propositionText) {
            return;
        }

        $existingComplaint = $this->repo->findOneBy([
            "translation" => $translation,
            "propositionText" => $propositionText
        ]);

        if (null !== $existingComplaint) {
            return $existingComplaint;
        }

        $complaint = new Complaint($translation, $propositionText);
        $this->entityManager->persist($complaint);

        return $complaint;
    }
}
