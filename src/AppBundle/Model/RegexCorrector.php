<?php
namespace AppBundle\Model;

use AppBundle\Tool\StringComparer;

class RegexCorrector implements CorrectorInterface
{
    const THRESHOLD_FOR_GUESSING = 8;
    const THRESHOLD_FOR_GUESSING_WORD = 2;
    const THRESHOLD_FOR_ACCEPTING = 1;

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

        if ($distance <= self::THRESHOLD_FOR_ACCEPTING) {
            $correction->setIsOkDespiteRemark(true);
        }

        if ($distance < self::THRESHOLD_FOR_GUESSING) {
            $correctedAnswer = $this->generateCorrectedAnswer(
                $proposition->getText(),
                $closestGoodAnswer
            );
            $correction->addRemark("Vouliez-vous dire \"$correctedAnswer\" ?");
        }
        else {
            $correction->addRemark("G rien compri lol");
        }

        return $correction;
    }

    public function generateCorrectedAnswer($actual, $expected)
    {
        $correctedAnswer = "";
        $actualWords = explode(' ', $actual);
        $expectedWords = explode(' ', $expected);

        $actualWordSelectionOffset = 0;

        foreach ($expectedWords as $index => $expectedWord) {
            $actualWord = "";

            if (isset($actualWords[$index + $actualWordSelectionOffset])) {
                $actualWord = $actualWords[$index + $actualWordSelectionOffset];
            }

            $nextActualWord = "";

            if (isset($actualWords[$index + $actualWordSelectionOffset + 1])) {
                $nextActualWord = $actualWords[$index + $actualWordSelectionOffset + 1];
            }

            $nextexpectedWord = "";

            if (isset($expectedWords[$index + 1])) {
                $nextexpectedWord = $expectedWords[$index + 1];
            }

            if ($expectedWord != $actualWord) {
                if ($nextActualWord == $expectedWord) {
                    $correctedAnswer .= "<strike>$actualWord</strike>";
                    $actualWordSelectionOffset ++;

                    $actualWord = "";

                    if (isset($actualWords[$index + $actualWordSelectionOffset])) {
                        $actualWord = $actualWords[$index + $actualWordSelectionOffset];
                    }

                    $nextActualWord = "";

                    if (isset($actualWords[$index + $actualWordSelectionOffset])) {
                        $nextActualWord = $actualWords[$index + $actualWordSelectionOffset + 1];
                    }
                }

                if ($actualWord == $nextexpectedWord) {
                    $correctedAnswer .= "<strong>$expectedWord</strong>";
                    $actualWordSelectionOffset ++;

                    continue;
                }

                $correctedAnswer .= "<strike>$actualWord</strike> <strong>$expectedWord</strong>";
            }
            else {
                $correctedAnswer .= $actualWord;
            }

            $correctedAnswer .= " ";
        }

        return trim($correctedAnswer);
    }
}
?>
