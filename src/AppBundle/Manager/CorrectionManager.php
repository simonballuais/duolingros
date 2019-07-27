<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\BaseManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;

use AppBundle\Services\Mailer;
use AppBundle\Model\CorrectionInterface;
use AppBundle\Model\Exercise;
use AppBundle\Model\PropositionInterface;
use AppBundle\Entity\Translation;
use AppBundle\Entity\Question;

class CorrectionManager
{
    const SERVICE_NAME = 'app.correction_manager';

    protected $regexCorrector;

    public function __construct($regexCorrector)
    {
        $this->regexCorrector = $regexCorrector;
    }

    public function correct(
        Exercise $exercise,
        PropositionInterface $proposition
    ): CorrectionInterface {
        $corrector = $this->determineCorrector($exercise);
        $correction = $corrector->correct($exercise, $proposition);

        return $correction;
    }

    private function determineCorrector(Exercise $exercise)
    {
        if ($exercise instanceof Translation) {
            return $this->regexCorrector;
        }
    }
}
