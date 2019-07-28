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
        Exercise $question,
        PropositionInterface $proposition
    ) {
        $correction = new Correction();

        if (! $question->getAnswer()) {
            $this->logger->critical(sprintf(
                'The question %s has no answer set',
                $question->getId()
            ));

            $correction->setRightAnswer($question->getPropositionList()->first());

            return $correction;
        }

        if ($proposition->getValue() != $question->getAnswer()->getId()) {
            $correction->addRemark(sprintf(
                'La bonne réponse était : <b>%s</b>',
                $question->getAnswer()->getText()
            ));
        }

        $correction->setRightAnswer($question->getAnswer());

        return $correction;
    }
}

