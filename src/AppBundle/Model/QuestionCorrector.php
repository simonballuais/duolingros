<?php
namespace AppBundle\Model;

use AppBundle\Model\Exercise;
use AppBundle\Tool\StringComparer;

class QuestionCorrector implements CorrectorInterface
{
    protected $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function correct(
        Exercise $translation,
        PropositionInterface $proposition
    ) {
        $correction = new Correction();
        return $correction;
    }
}

