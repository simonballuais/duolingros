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
use AppBundle\Model\RegexCorrector;
use AppBundle\Model\QuestionCorrector;
use AppBundle\Entity\Translation;
use AppBundle\Entity\Question;

class CorrectionManager
{
    const SERVICE_NAME = 'app.correction_manager';

    protected $regexCorrector;

    public function __construct(
        RegexCorrector $regexCorrector,
        QuestionCorrector $questionCorrector
    ) {
        $this->regexCorrector = $regexCorrector;
        $this->questionCorrector = $questionCorrector;
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

        if ($exercise instanceof Question) {
            return $this->questionCorrector;
        }
    }
}
