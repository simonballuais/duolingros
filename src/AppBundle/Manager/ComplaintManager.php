<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use AppBundle\Entity\Complaint;


class ComplaintManager
{
    const SERVICE_NAME = 'app.complaint_manager';

    protected $entityManager;
    protected $session;
    protected $repo;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repo = $entityManager->getRepository("AppBundle:Complaint");
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
