<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use AppBundle\Model\PropositionInterface;
use AppBundle\Model\Exercise;

/**
* @ORM\Entity
* @ORM\Table(name="translation")
*
* @Serializer\ExclusionPolicy("all")
*/
class Translation implements Exercise
{
    public $NOT_A_GROUP_DELIMITER_REGEX; // not ( [ {
    public $OPTION_GROUP_REGEX; // to find imediatly resolvable option groups such as (a|b|c) but not (a|b (c|d)|e)

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("id")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=225)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("text")
     */
    protected $text;

    /**
     * @ORM\Column(type="array")
     */
    protected $answerList;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="translationList", cascade={"persist"})
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     */
    protected $lesson;

    /**
     * @ORM\Column(type="integer", options={"default":1}, nullable=true)
     */
    protected $difficulty;

    public function __construct()
    {
        $this->NOT_A_GROUP_DELIMITER_REGEX = '[^\(\{\[]';
        $this->OPTION_GROUP_REGEX = sprintf(
            '/\(%s*?\|%s*?\)/',
           $this->NOT_A_GROUP_DELIMITER_REGEX,
           $this->NOT_A_GROUP_DELIMITER_REGEX
       );
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
            $concreteAnswerList = array_merge(
                $concreteAnswerList,
                $this->concretiseAnswer($answer)
            );
        }

        return $concreteAnswerList;
    }

    public function concretiseAnswer($answerList)
    {
        if (!is_array($answerList)) {
            $answerList = [$answerList];
        }

        $concretisedAnswerList = [];

        foreach ($answerList as $answer) {
            $optionGroups = $this->findNonRecursiveOptionGroups($answer);
            $probablyConcretisedAnswerList = $this->distributeOptionGroups($answer, $optionGroups);

            foreach ($probablyConcretisedAnswerList as $probablyConcretisedAnswer) {
                $optionGroups = $this->findNonRecursiveOptionGroups($probablyConcretisedAnswer);

                if (count($optionGroups)) {
                    $actuallyConcretisedAnswers = $this->concretiseAnswer($probablyConcretisedAnswer);

                    $concretisedAnswerList = array_merge(
                        $concretisedAnswerList,
                        $actuallyConcretisedAnswers
                    );
                }
                else {
                    $concretisedAnswerList[] = trim($probablyConcretisedAnswer);
                }
            }
        }

        $concretisedAnswerList = array_flip(array_flip($concretisedAnswerList));

        return $concretisedAnswerList;
    }

    public function distributeOptionGroups($answer, $optionGroups)
    {
        return $this->recursiveDistribution([$answer], $optionGroups);
    }

    public function recursiveDistribution($answerList, $optionGroups)
    {
        $distributedAnswerList = [];

        $optionGroup = array_pop($optionGroups);

        $options = str_replace('(', '', $optionGroup);
        $options = str_replace(')', '', $options);
        $options = explode('|', $options);

        $replaceTarget = str_replace('|', '\|', $optionGroup);
        $replaceTarget = str_replace('(', '\(', $replaceTarget);
        $replaceTarget = str_replace(')', '\)', $replaceTarget);
        $replaceTarget = '/' . $replaceTarget . '/';

        foreach ($answerList as $distributableAnswer) {
            foreach ($options as $option) {
                $distributedAnswerList[] = preg_replace(
                    $replaceTarget,
                    $option,
                    $distributableAnswer,
                    1
                );
            }
        }

        if (count($optionGroups)) {
            return $this->recursiveDistribution($distributedAnswerList, $optionGroups);
        }

        return $distributedAnswerList;
    }

    public function findNonRecursiveOptionGroups($candidate)
    {
        $this->NOT_A_GROUP_DELIMITER_REGEX = '[^\(\{\[]';
        $this->OPTION_GROUP_REGEX = sprintf(
            '/\(%s*?\|%s*?\)/',
           $this->NOT_A_GROUP_DELIMITER_REGEX,
           $this->NOT_A_GROUP_DELIMITER_REGEX
       );
        $result = [];
        preg_match_all($this->OPTION_GROUP_REGEX, $candidate, $result);

        if (!isset($result[0])) {
            return "";
        }

        $optionGroups = $result[0];
        $optionGroups = array_reverse($optionGroups);

        return $optionGroups;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("type")
     */
    public function getExerciseType()
    {
        return 'translation';
    }

    public function getPossiblePropositions()
    {
        return null;
    }

    public function getDifficulty(): int
    {
        if (null === $this->difficulty) {
            return 1;
        }

        return $this->difficulty;
    }

    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }
}
