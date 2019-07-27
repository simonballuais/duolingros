<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;

use AppBundle\Services\Mailer;
use AppBundle\Entity\Translation;

class TranslationManager
{
    const SERVICE_NAME = 'app.translation_manager';

    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createOrUpdate($text, $answerList, $lesson)
    {
        $translation = new Translation();
        $translation->setText($text);
        $translation->setAnswerList($answerList);
        $translation->setLesson($lesson);

        $this->entityManager->merge($translation);

        return $translation;
    }

    public function get($id)
    {
        $repoTranslation = $this->entityManager->getRepository("AppBundle:Translation");
        $translation = $repoTranslation->findOneById($id);

        return $translation;
    }

    public function getAll()
    {
        $repoTranslation = $this->entityManager->getRepository("AppBundle:Translation");
        $translations = $repoTranslation->findAll();

        return $translations;
    }
}
