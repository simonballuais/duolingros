<?php

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Services\Mailer;
use App\Entity\Translation;

class TranslationManager
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createOrUpdate($text, $answerList, $lesson)
    {
        $translation = new Translation();
        $translation->setText($text);
        $translation->setAnswerList($answerList);
        $translation->setLesson($lesson);

        $this->em->merge($translation);

        return $translation;
    }

    public function get($id)
    {
        $repoTranslation = $this->em->getRepository("App:Translation");
        $translation = $repoTranslation->findOneById($id);

        return $translation;
    }

    public function getAll()
    {
        $repoTranslation = $this->em->getRepository("App:Translation");
        $translations = $repoTranslation->findAll();

        return $translations;
    }
}
