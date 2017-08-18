<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Model\Correction;
use AppBundle\Model\RegexCorrector;
use AppBundle\Model\PropositionInterface;


/**
* @ORM\Entity
* @ORM\Table(name="exercise")
*/
class Exercise
{
    /**
     * @ORM\Column(type="string", length=300)
     * @ORM\Id
     */
    protected $text;

    /**
     * @ORM\Column(type="array")
     */
    protected $answerList;

    protected $corrector;

    public function __construct()
    {
        $this->corrector = new RegexCorrector();
    }

    public function treatProposition(PropositionInterface $proposition)
    {
        $this->corrector = new RegexCorrector();
        return $this->corrector->correct($this->answerList, $proposition);
    }

    public function matches(PropositionInterface $proposition)
    {
        return $this->caseInsensitiveStrictMatches($proposition);
    }

    public function strictMatches(PropositionInterface $proposition)
    {
        return $this->text === $proposition->getText();
    }

    public function caseInsensitiveStrictMatches(PropositionInterface $proposition)
    {
        return preg_match(sprintf("/%s/i", $proposition->getText()), $this->text);
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

    public function getAnswerList()
    {
        return $this->answerList;
    }

    public function setAnswerList($answerList)
    {
        $this->answerList = $answerList;

        return $this;
    }

    public function __toString()
    {
        return sprintf("%s\n    %s",
            $this->text,
            implode("\n    ", $this->answerList)
        );
    }
}
