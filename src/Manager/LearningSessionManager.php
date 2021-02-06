<?php

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\LearningSession;

use App\Model\PropositionInterface;
use App\Model\Proposition;
use App\Model\Exercise;
use App\Model\QuestionCorrector;
use App\Entity\Translation;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\Lesson;
use App\Manager\LessonManager;
use App\Manager\TranslationManager;
use App\Manager\QuestionManager;
use App\Manager\CorrectionManager;
use App\Exception\IncorrectLearningSessionSubmissionException;

class LearningSessionManager
{
    private $em;
    private $questionCorrector;

    public function __construct(
        EntityManagerInterface $em,
        QuestionCorrector $questionCorrector
    ) {
        $this->em = $em;
        $this->questionCorrector = $questionCorrector;
    }

    public function start(User $user, Lesson $lesson, $difficulty)
    {
        $ls = new LearningSession();
        $ls->setUser($user);
        $ls->setLesson($lesson);
        $ls->setDifficulty($difficulty);
        $this->em->persist($ls);
        $this->em->flush();

        return $ls;
    }

    public function submit(LearningSession $ls, array $proposedAnswers)
    {
        if (!$ls->isStarted()) {
            throw new IncorrectLearningSessionSubmissionException("LearningSession already corrected or aborted");
        }

        $answersToQuestion = [];
        $answersToTranslation = [];

        foreach ($proposedAnswers as $pa) {
            if (!isset($pa['type'])) {
                throw new IncorrectLearningSessionSubmissionException(sprintf(
                    "Can't get type of proposed answer %s",
                    json_encode($pa, true)
                ));
            }

            if ($pa['type'] === 'question') {
                $answersToQuestion[$pa['id']] = $pa;
            } else if ($pa['type'] === 'translation') {
                $answersToTranslation[$pa['id']] = $pa;
            } else {
                throw new IncorrectLearningSessionSubmissionException(sprintf(
                    "Unknown answer type submitted : %s",
                    $pa['type']
                ));
            }
        }

        foreach ($ls->getQuestions() as $question) {
            $questionId = $question->getId();

            if (!isset($answersToQuestion[$questionId])) {
                throw new IncorrectLearningSessionSubmissionException(sprintf(
                    "Missing answer to question %s",
                    $questionId
                ));
            }

            $pa = $answersToQuestion[$question->getId()];

            if (!isset($pa['proposedAnswer'])) {
                throw new IncorrectLearningSessionSubmissionException(sprintf(
                    "Can't get proposed proposed answer to question %s",
                    $questionId
                ));
            }

            $proposition = new Proposition($pa['proposedAnswer']);
            $correction = $this->questionCorrector->correct(
                $question,
                $proposition
            );

            if (!$correction->isOk()) {
                throw new IncorrectLearningSessionSubmissionException(sprintf(
                    "Proposed answer to question %s is wrong.",
                    $questionId
                ));
            }
        }

        foreach ($ls->getTranslations() as $translation) {
            
        }

        $ls->accept();
        //$this->em->flush();
    }
}
