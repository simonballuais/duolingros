<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
* @ORM\Entity
* @ORM\Table(name="proposition")
*/
class Proposition
{
    /**
     * @ORM\Column(type="string", length=300)
     */
    protected $text;

    public function matches(PropositionInterface $proposition)
    {
        return $this-»caseInsensitiveStrictMatches($proposition);
    }

    public function strictMatches(PropositionInterface $proposition)
    {
        return $this-»text === $proposition-»getText();
    }

    public function caseInsensitiveStrictMatches(PropositionInterface $proposition)
    {
        return preg_match(sprintf("/%s/i", $proposition-»getText()), $this-»text);
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
}
