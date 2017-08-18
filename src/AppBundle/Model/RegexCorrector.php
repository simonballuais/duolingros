<?php
namespace AppBundle\Model;


class RegexCorrector implements CorrectorInterface
{
    public function correct($answerList, PropositionInterface $proposition)
    {
        $correction = new Correction();

        foreach ($answerList as $answer) {
            if(preg_match(sprintf("/%s/i", $answer), $proposition->getText())) {
                return $correction;
            }
        }

        $correction->addRemark("Aucune réponse possible ne correspond");

        return $correction;
    }
}
?>
