<?php
namespace App\Tool;

class StringComparer
{
    static function findClosestCandidate($model, $candidates)
    {
        if (!isset($candidates[0])) {
            return null;
        }

        $bestCandidate = $candidates[0];
        $bestScore = levenshtein($model, $bestCandidate);

        foreach ($candidates as $candidate) {
            $score = levenshtein($model, $candidate);

            if ($score < $bestScore) {
                $bestCandidate = $candidate;
                $bestScore = $score;
            }
        }

        return $bestCandidate;
    }
}
?>
