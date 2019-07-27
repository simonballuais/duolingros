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
    protected $defaultCorrector;

    public function __construct($entityManager, $defaultCorrector)
    {
        $this->entityManager = $entityManager;
        $this->defaultCorrector = $defaultCorrector;
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

    public function get($text)
    {
        $repoTranslation = $this->entityManager->getRepository("AppBundle:Translation");
        $translation = $repoTranslation->findOneBy(['text' => $text]);
        $translation->setCorrector($this->defaultCorrector);

        return $translation;
    }

    public function getAll()
    {
        $repoTranslation = $this->entityManager->getRepository("AppBundle:Translation");
        $translations = $repoTranslation->findAll();
        $translation->setCorrector($this->defaultCorrector);

        return $translations;
    }
}
