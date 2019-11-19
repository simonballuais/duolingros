<?php

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Services\Mailer;
use App\Entity\Question;

class QuestionManager
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get($id)
    {
        $repoQuestion = $this->em->getRepository("App:Question");
        $question = $repoQuestion->findOneById($id);

        return $question;
    }

    public function getAll()
    {
        $repoQuestion = $this->em->getRepository("App:Question");
        $questions = $repoQuestion->findAll();

        return $questions;
    }
}
