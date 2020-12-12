<?php
namespace App\Model;

class Proposition implements PropositionInterface
{
    protected $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function getValue()
    {
        return $this->text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function __toString()
    {
        return $this->text ?? "";
    }
}

