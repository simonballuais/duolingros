<?php
namespace AppBundle\Model;

interface CorrectorInterface
{
    public function correct($answerList, PropositionInterface $proposition);
}
?>
