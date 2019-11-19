<?php
namespace App\Model;

use App\Model\Exercise;

interface CorrectorInterface
{
    public function correct(Exercise $exercise, PropositionInterface $proposition);
}

