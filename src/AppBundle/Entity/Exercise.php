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
    const OPTION_GROUP_REGEX = '/\(.*?\|.*?\)/';

    /**
     * @ORM\Column(type="string", length=300)
     * @ORM\Id
     */
    protected $text;

    /**
     * @ORM\Column(type="array")
     */
    protected $answerList;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="exerciseList", cascade={"persist"})
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     */
    protected $lesson;

    protected $corrector;

    public function __construct()
    {
        $this->corrector = new RegexCorrector();
    }

    public function treatProposition(PropositionInterface $proposition)
    {
        $this->corrector = new RegexCorrector();
        return $this->corrector->correct($this->getConcreteAnswerList(), $proposition);
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

    public function getLesson()
    {
        return $this->lesson;
    }

    public function setLesson($lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }
    public function getConcreteAnswerList()
    {
        $concreteAnswerList = [];

        foreach ($this->answerList as $answer) {
            $concreteAnswerList[] = $answer;

            $result = [];
            preg_match_all(self::OPTION_GROUP_REGEX, $answer, $result);

            if (!isset($result[0])) {
                continue;
            }

            $optionGroups = $result[0];

            $concreteAnswerList[] = $this->concretiseAnswer([$answer], $optionGroups);
        }

        var_dump($concreteAnswerList);die;
    }

    public function concretiseAnswer($answerList, $optionGroups)
    {
        $concretisedAnswerList = [];
        $optionGroup = array_pop($optionGroups);
        $options = explode('|', $optionGroup);

        foreach ($answerList as $concretisableAnswer) {
            foreach ($options as $option) {
                $concretisedAnswerList[] = preg_replace(
                    self::OPTION_GROUP_REGEX,
                    $option,
                    $concretisableAnswer,
                    1
                );
            }
        }

        if (count($optionGroups)) {
            return $this->concretiseAnswer($concretisedAnswerList, $optionGroups);
        }

        return $concretisedAnswerList;
    }
}
