<?php
namespace AppBundle\Model;

use AppBundle\Model\Exercise;

interface CorrectorInterface
{
    public function correct(Exercise $exercise, PropositionInterface $proposition);
}

