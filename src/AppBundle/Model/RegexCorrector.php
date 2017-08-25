<?php
namespace AppBundle\Model;

use AppBundle\Tool\StringComparer;

class RegexCorrector implements CorrectorInterface
{
    const THRESHOLD_FOR_GUESSING = 4;

    public function correct($answerList, PropositionInterface $proposition)
    {
        $correction = new Correction();

        foreach ($answerList as $answer) {
            if(preg_match(sprintf("/^%s$/i", $answer), $proposition->getText())) {
                return $correction;
            }
        }

        $closestGoodAnswer = StringComparer::findClosestCandidate(
            $proposition->getText(),
            $answerList
        );

        $distance = levenshtein($proposition->getText(), $closestGoodAnswer);

        if ($distance < self::THRESHOLD_FOR_GUESSING) {
            $correction->addRemark("Réponse la plus proche :");
            $correction->addRemark($closestGoodAnswer);
        }
        else {
            $correction->addRemark("G rien compri lol");
        }

        return $correction;
    }
}
?>
