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
        $this->logger->info("Début de correction de $proposition");

        $correction = new Correction();

        $purifiedProposition = $this->purifyString($proposition->getText());
        $this->logger->info("Proposition analysée : $purifiedProposition");

        foreach ($answerList as $answer) {
            $purifiedAnswer = $this->purifyString($answer);
            $this->logger("Réponse envisagée : $purifiedAnswer");

            if(preg_match(sprintf("/^%s$/i", $purifiedAnswer), $purifiedProposition)) {
                $this->logger("Match parfait trouvé");
                return $correction;
            }
        }

        $this->logger("Aucun match parfait trouvé");

        $closestGoodAnswer = StringComparer::findClosestCandidate(
            $proposition->getText(),
            $answerList
        );

        $purifiedAnswer = $this->purifyString($closestGoodAnswer);
        $this->logger("Réponse la plus proche de la proposition : $purifiedAnswer");

        $distance = levenshtein($purifiedAnswer, $purifiedProposition);
        $this->logger("Distance avec la proposition : $distance");

        if ($distance <= self::THRESHOLD_FOR_ACCEPTING) {
            $this->logger("Réponse acceptée malgré la distance");
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
        $this->logger("Génération d'une proposition corrigée (\"$actual\" vs \"$expected\")");
        $correctedAnswer = "";
        $actualWords = explode(' ', $actual);
        $expectedWords = explode(' ', $expected);

        $this->logger(sprintf("Mots attendus : ", explode('|', $expectedWords)));
        $this->logger(sprintf("Mots obtenus : ", explode('|', $actualWords)));

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

            $nextExpectedWord = "";

            if (isset($expectedWords[$index + 1])) {
                $nextExpectedWord = $expectedWords[$index + 1];
            }

            $this->logger("Comparaison de $expectedWords vs $actualWord");

            if ($expectedWord != $actualWord) {
                $this->logger("Échec de la comparaison");

                if ($nextActualWord == $expectedWord) {
                    $correctedAnswer .= "<strike>$actualWord</strike>";
                    $this->logger("Il y avait un mot de trop ($actualWord). On raye le mot actuel");

                    $actualWordSelectionOffset ++;
                    $actualWord = "";

                    if (isset($actualWords[$index + $actualWordSelectionOffset])) {
                        $actualWord = $actualWords[$index + $actualWordSelectionOffset];
                    }

                    $nextActualWord = "";

                    if (isset($actualWords[$index + $actualWordSelectionOffset + 1])) {
                        $nextActualWord = $actualWords[$index + $actualWordSelectionOffset + 1];
                    }

                    continue;
                }

                if ($actualWord == $nextExpectedWord) {
                    $correctedAnswer .= "<strong>$expectedWord</strong>";
                    $this->logger("Il y avait un oubli mot ($expectedWord). On le met en gras");

                    continue;
                }

                $correctedAnswer .= "<strike>$actualWord</strike> <strong>$expectedWord</strong>";
                $this->logger("Erreur simple. Rayage et remplacement");
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
