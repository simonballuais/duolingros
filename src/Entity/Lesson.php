<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups as JMSGroups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\AccessorOrder;
use ApiPlatform\Core\Annotation as API;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson")
 * @ExclusionPolicy("all")
 *
 * @API\ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"writeLesson"}},
 *     attributes={"securit"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')",
 *              "normalization_context"={"groups"={"readCollection"}}
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"writeCollection"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')",
 *              "normalization_context"={"groups"={"readItem"}}
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"writeItem"}}
 *          }
 *     }
 * )
 *
 * @API\ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "bookLesson": "exact"
 *      }
 * )
 */
class Lesson
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"readCollection", "writeCollection", "writeItem", "readItem",  "writeLesson", "startLearningSession"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"readCollection", "writeCollection", "writeItem", "readItem", "writeLesson", "startLearningSession"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     *
     * @Groups({"readCollection", "writeCollection", "writeItem", "readItem", "writeLesson"})
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="lesson", cascade={"persist"})
     * @ORM\OrderBy({"difficulty": "ASC"})
     *
     * @Groups({"writeItem", "readItem", "writeLesson"})
     */
    protected $translations;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="lesson", cascade={"persist"})
     * @ORM\OrderBy({"difficulty": "ASC"})
     *
     * @Groups({"writeItem", "readItem", "writeLesson"})
     */
    protected $questions;

    /**
     * @ORM\OneToMany(targetEntity="Learning", mappedBy="lesson", cascade={"persist"})
     */
    protected $learnings;

    /**
     * @ORM\OneToMany(targetEntity="LearningSession", mappedBy="lesson", cascade={"persist"})
     */
    protected $learningSessions;

    /**
     * @ORM\ManyToOne(targetEntity="BookLesson", inversedBy="lessons", cascade={"persist"})
     * @ORM\JoinColumn(name="book_lesson_id", referencedColumnName="id")
     *
     * @Groups({"writeCollection", "writeItem", "writeLesson", "startLearningSession"})
     */
    protected $bookLesson;

    /**
     * @ORM\Column(type="integer", name="lesson_order")
     *
     * @Groups({"writeItem", "readItem", "writeLesson"})
     */
    protected $order;

    /**
     * @ORM\OneToMany(targetEntity="UnlockedLesson", mappedBy="lesson", cascade={"persist"})
     */
    protected $unlockedLessons;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="childrenLessons", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="parent", cascade={"persist"})
     */
    protected $childrenLessons;

    protected $currentLearning;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->learnings = new ArrayCollection();
        $this->learningSessions = new ArrayCollection();
        $this->translationPerStudy = 3;
        $this->unlockedLessons = new ArrayCollection();
        $this->childrenLesson = new ArrayCollection();
        $this->order = 1;
    }

     /**
      * @JMSGroups({"Default"})
      * @SerializedName("title")
      * @VirtualProperty
      */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

     /**
      * @JMSGroups({"Default"})
      * @SerializedName("id")
      * @VirtualProperty
      */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getRandomExercise(
        $except = null,
        $maxDifficulty = 0
    ) {
        $pickables = $this->getAllExercises();

        if (count($pickables) === 1) {
            return $pickables[0];
        }

        $i = 0;

        do {
            $result = $this->pickRandomExercise();
            $i += 1;

            $isResultBad = $result->getDifficulty() > $maxDifficulty
                || $result->getId() === $except;
        } while ($i <= 100 && $isResultBad);

        return $result;
    }

    public function pickRandomExercise()
    {
        $pickables = $this->getAllExercises();

        return $pickables[array_rand($pickables)];
    }

    public function getAllExercises()
    {
        return array_merge(
            $this->translations->toArray(),
            $this->questions->toArray()
        );

    }

    public function getAllExerciseGroupedByDifficulty()
    {
        $exercises = [];

        foreach ($this->getAllExercises() as $exercise) {
            $exercises[$exercise->getDifficulty()] = $exercise;
        }

        return $exercises;
    }

    public function __toString()
    {
        return sprintf("Lesson [%s] - %s", $this->id, $this->title);
    }

    public function getLearnings()
    {
        return $this->learnings;
    }

    public function setLearnings($learnings)
    {
        $this->learnings = $learnings;

        return $this;
    }

    public function getLearningSessions()
    {
        return $this->learningSessions;
    }

    public function setLearningSessions($learningSessions)
    {
        $this->learningSessions = $learningSessions;

        return $this;
    }

    public function setCurrentLearning($currentLearning)
    {
        $this->currentLearning = $currentLearning;

        return $this;
    }

     /**
      * @JMSGroups({"Default"})
      * @SerializedName("currentLearning")
      * @VirtualProperty
      */
    public function getCurrentLearning()
    {
        return $this->currentLearning;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function setBookLesson($bookLesson)
    {
        $this->bookLesson = $bookLesson;

        return $this;
    }

    public function getBookLesson()
    {
        return $this->bookLesson;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function setQuestions($questions)
    {
        $this->questions = $questions;

        return $this;
    }

    public function addQuestion($question)
    {
        $this->questions[] = $question;
        $question->setLesson($this);
    }

    public function removeQuestion($question)
    {
        $this->questions->removeElement($question);
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function setTranslations($translations)
    {
        $this->translations = $translations;

        return $this;
    }

    public function addTranslation($translation)
    {
        $this->translations[] = $translation;
        $translation->setLesson($this);
    }

    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    public function getUnlockedLessons()
    {
        return $this->unlockedLessons;
    }

    public function setUnlockedLessons($unlockedLessons)
    {
        $this->unlockedLessons = $unlockedLessons;

        return $this;
    }

    public function isUnlockedForUser(User $user): bool
    {
        if (!$this->parent) {
            return true;
        }

        foreach ($this->unlockedLessons as $unlockedLesson) {
            if ($unlockedLesson->getUser()->getId() === $user->getId()
                && $unlockedLesson->isUnlocked()
            ) {
                return true;
            }
        }

        return false;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildrenLessons()
    {
        return $this->childrenLessons;
    }

    public function setChildrenLessons($childrenLessons)
    {
        $this->childrenLessons = $childrenLessons;

        return $this;
    }

    public function getTranslationsOfDifficulty($difficulty)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('difficulty', $difficulty))
        ;

        return $this->translations->matching($criteria);
    }

    public function getQuestionsOfDifficulty($difficulty)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('difficulty', $difficulty))
        ;

        return $this->questions->toArray();
    }
}
