<?php
namespace AppBundle\Model;

use AppBundle\Tool\StringComparer;

class RegexCorrector implements CorrectorInterface
{
    const THRESHOLD_FOR_GUESSING = 8;
    const THRESHOLD_FOR_GUESSING_WORD = 2;
    const THRESHOLD_FOR_ACCEPTING = 1;

    protected $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function correct($answerList, PropositionInterface $proposition)
    {
        $correction = new Correction();

        foreach ($answerList as $answer) {
            $purifiedAnswer = $this->purifyString($answer);
            $purifiedProposition = $this->purifyString($proposition->getText());

            if(preg_match(sprintf("/^%s$/i", $purifiedAnswer), $purifiedProposition)) {
                return $correction;
            }
        }

        $closestGoodAnswer = StringComparer::findClosestCandidate(
            $proposition->getText(),
            $answerList
        );

        $purifiedAnswer = $this->purifyString($closestGoodAnswer);
        $purifiedProposition = $this->purifyString($proposition->getText());

        $distance = levenshtein($purifiedAnswer, $purifiedProposition);

        if ($distance <= self::THRESHOLD_FOR_ACCEPTING) {
            $correction->setIsOkDespiteRemark(true);
        }

        if ($distance != 0) {
            if ($distance <= self::THRESHOLD_FOR_GUESSING) {
                $correctedAnswer = $this->generateCorrectedAnswer(
                    $proposition->getText(),
                    $closestGoodAnswer
                );

                $correction->addRemark("Vouliez-vous dire \"$correctedAnswer\" ?");
            }
            else {
                $correctedAnswer = "<strong>" . $closestGoodAnswer . "<strong>";
                $correction->addRemark("On aurait pu dire : \"$correctedAnswer\" ?");
            }

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

                    if (isset($actualWords[$index + $actualWordSelectionOffset + 1])) {
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

    public function purifyString($string)
    {
        $purifiedString = preg_replace('/[.,:;?\']/', ' ', $string);
        $purifiedString = strtolower($purifiedString);
        $purifiedString = preg_replace('/  /', ' ', $purifiedString);
        $purifiedString = preg_replace('/  /', ' ', $purifiedString);
        $purifiedString = preg_replace('/  /', ' ', $purifiedString);
        $purifiedString = preg_replace('/  /', ' ', $purifiedString);
        $purifiedString = preg_replace('/[éèê]/', 'e', $purifiedString);
        $purifiedString = preg_replace('/[àâ]/', 'a', $purifiedString);
        $purifiedString = preg_replace('/[îï]/', 'i', $purifiedString);
        $purifiedString = preg_replace('/[îï]/', 'i', $purifiedString);
        $purifiedString = trim($purifiedString);

        return $purifiedString;
    }
}
?>
