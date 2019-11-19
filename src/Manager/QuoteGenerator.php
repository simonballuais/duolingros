<?php

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use App\Manager\BaseManager;
use App\Entity\Complaint;


class QuoteGenerator
{
    const SERVICE_NAME = 'app.quote_generator';

    protected $titleQuotes;

    public function __construct()
    {
        $this->titleQuotes = [
            'Le gasy pour les durs',
            'Le gasy pour ceux qui en veulent',
            'Le gasy pour ceux qui en ont',
            'Prendre le gasy par les cornes',
            'Bonzour vazaha',
            'Je gasy, tu gasy, il gasy',
        ];
    }

    public function generateTitleQuote()
    {
        return $this->titleQuotes[rand(0, count($this->titleQuotes) - 1)];
    }
}
