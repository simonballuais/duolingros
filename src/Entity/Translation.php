<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

use ApiPlatform\Core\Annotation as API;

use App\Model\PropositionInterface;
use App\Model\Exercise;

/**
 * @ORM\Entity
 * @ORM\Table(name="translation")
 *
 * @API\ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"securit"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_USER')"},
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"writeCollection"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_USER')"},
 *          "put"={"security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
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
     * @Groups({"read", "writeLesson", "readItem", "startLearningSession"})
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("id")
     * @Serializer\Groups({"startLearningSession"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=225)
     *
     * @Groups({"read", "writeLesson", "readItem", "startLearningSession"})
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("text")
     * @Serializer\Groups({"startLearningSession"})
     */
    protected $text;

    /**
     * @ORM\Column(type="array")
     *
     * @Groups({"read", "writeLesson", "readItem", "startLearningSession"})
     * @Serializer\Expose()
     * @Serializer\Groups({"startLearningSession"})
     */
    protected $answers;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="translations", cascade={"persist"})
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     */
    protected $lesson;

    /**
     * @ORM\Column(type="integer", options={"default":1}, nullable=true)
     *
     * @Groups({"read", "writeLesson", "readItem"})
     */
    protected $difficulty;

    /**
     * @ORM\OneToMany(targetEntity="Complaint", mappedBy="translation", cascade={"persist", "remove"})
     */
    protected $complaints;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read", "readItem", "startLearningSession"})
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("words")
     * @Serializer\Groups({"startLearningSession"})
     */
    protected $words;

    public function __construct()
    {
        $this->NOT_A_GROUP_DELIMITER_REGEX = '[^\(\{\[]';
        $this->OPTION_GROUP_REGEX = sprintf(
            '/\(%s*?\|%s*?\)/',
           $this->NOT_A_GROUP_DELIMITER_REGEX,
           $this->NOT_A_GROUP_DELIMITER_REGEX
        );
        $this->complaints = new ArrayCollection();
        $this->words = [];
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

    public function getAnswers()
    {
        if (null === $this->answers) {
            $this->answers = [];
        }

        return array_values($this->answers);
    }

    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }

    public function __toString()
    {
        return sprintf("%s\n    %s",
            $this->text,
            implode("\n    ", $this->answers)
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

    public function getConcreteAnswers()
    {
        $concreteAnswers = [];

        foreach ($this->answers as $answer) {
            $concreteAnswers = array_merge(
                $concreteAnswers,
                $this->concretiseAnswer($answer)
            );
        }

        return $concreteAnswers;
    }

    public function concretiseAnswer($answers)
    {
        if (!is_array($answers)) {
            $answers = [$answers];
        }

        $concretisedAnswers = [];

        foreach ($answers as $answer) {
            $optionGroups = $this->findNonRecursiveOptionGroups($answer);
            $probablyConcretisedAnswers = $this->distributeOptionGroups($answer, $optionGroups);

            foreach ($probablyConcretisedAnswers as $probablyConcretisedAnswer) {
                $optionGroups = $this->findNonRecursiveOptionGroups($probablyConcretisedAnswer);

                if (count($optionGroups)) {
                    $actuallyConcretisedAnswers = $this->concretiseAnswer($probablyConcretisedAnswer);

                    $concretisedAnswers = array_merge(
                        $concretisedAnswers,
                        $actuallyConcretisedAnswers
                    );
                }
                else {
                    $concretisedAnswers[] = trim($probablyConcretisedAnswer);
                }
            }
        }

        $concretisedAnswers = array_flip(array_flip($concretisedAnswers));

        return $concretisedAnswers;
    }

    public function distributeOptionGroups($answer, $optionGroups)
    {
        return $this->recursiveDistribution([$answer], $optionGroups);
    }

    public function recursiveDistribution($answers, $optionGroups)
    {
        $distributedAnswers = [];

        $optionGroup = array_pop($optionGroups);

        $options = str_replace('(', '', $optionGroup);
        $options = str_replace(')', '', $options);
        $options = explode('|', $options);

        $replaceTarget = str_replace('|', '\|', $optionGroup);
        $replaceTarget = str_replace('(', '\(', $replaceTarget);
        $replaceTarget = str_replace(')', '\)', $replaceTarget);
        $replaceTarget = '/' . $replaceTarget . '/';

        foreach ($answers as $distributableAnswer) {
            foreach ($options as $option) {
                $distributedAnswers[] = preg_replace(
                    $replaceTarget,
                    $option,
                    $distributableAnswer,
                    1
                );
            }
        }

        if (count($optionGroups)) {
            return $this->recursiveDistribution($distributedAnswers, $optionGroups);
        }

        return $distributedAnswers;
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

    public function setWords($words): void
    {
        $this->words = $words;
    }

    public function getWords()
    {
        return $this->words;
    }
}
