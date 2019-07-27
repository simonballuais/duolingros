<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;

use AppBundle\Services\Mailer;
use AppBundle\Entity\Question;

class QuestionManager
{
    const SERVICE_NAME = 'app.question_manager';

    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function get($id)
    {
        $repoQuestion = $this->entityManager->getRepository("AppBundle:Question");
        $question = $repoQuestion->findOneById($id);

        return $question;
    }

    public function getAll()
    {
        $repoQuestion = $this->entityManager->getRepository("AppBundle:Question");
        $questions = $repoQuestion->findAll();

        return $questions;
    }
}
