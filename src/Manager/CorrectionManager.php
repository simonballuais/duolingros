<?php

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use App\Manager\BaseManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;

use App\Services\Mailer;
use App\Model\CorrectionInterface;
use App\Model\Exercise;
use App\Model\PropositionInterface;
use App\Model\RegexCorrector;
use App\Model\QuestionCorrector;
use App\Entity\Translation;
use App\Entity\Question;

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
