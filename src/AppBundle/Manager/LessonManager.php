<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use AppBundle\Services\Mailer;

class LessonManager
{
    const SERVICE_NAME = 'app.lesson_manager';

    protected $entityManager;
    protected $session;

    public function __construct($entityManager, $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }
}
