<?php

namespace App\Manager;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\LearningSession;

use App\Model\PropositionInterface;
use App\Model\Proposition;
use App\Model\Exercise;
use App\Model\QuestionCorrector;
use App\Model\RegexCorrector;
use App\Entity\Translation;
use App\Entity\BookLesson;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\Lesson;
use App\Entity\Progress;
use App\Manager\LessonManager;
use App\Manager\TranslationManager;
use App\Manager\QuestionManager;
use App\Manager\CorrectionManager;
use App\Exception\IncorrectLearningSessionSubmissionException;

class LearningSessionManager
{
    private $em;
    private $questionCorrector;
    private $translationCorrector;

    public function __construct(
        EntityManagerInterface $em,
        QuestionCorrector $questionCorrector,
        RegexCorrector $translationCorrector
    ) {
        $this->em = $em;
        $this->questionCorrector = $questionCorrector;
        $this->translationCorrector = $translationCorrector;
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
            $translationId = $translation->getId();

            if (!isset($answersToTranslation[$translationId])) {
                throw new IncorrectLearningSessionSubmissionException(sprintf(
                    "Missing answer to translation %s",
                    $translationId
                ));
            }

            $pa = $answersToTranslation[$translation->getId()];

            if (!isset($pa['proposedAnswer'])) {
                throw new IncorrectLearningSessionSubmissionException(sprintf(
                    "Can't get proposed proposed answer to translation %s",
                    $translationId
                ));
            }

            $proposition = new Proposition($pa['proposedAnswer']);
            $correction = $this->translationCorrector->correct(
                $translation,
                $proposition
            );

            if (!$correction->isOk()) {
                throw new IncorrectLearningSessionSubmissionException(sprintf(
                    "Proposed answer to translation %s is wrong.",
                    $translationId
                ));
            }
        }

        $user = $ls->getUser();
        $ls->accept();
        $this->updateProgress($user, $ls->getBookLesson());
        $user->incrementLearningSessionCountThatDay();
        $this->incrementSerieIfNeeded($user);
        $this->em->flush();
    }

    public function updateProgress($user, BookLesson $bookLesson)
    {
        $progress = $this->em->getRepository(Progress::class)->findOneBy([
            'user' => $user,
            'bookLesson' => $bookLesson
        ]);

        if (!$progress) {
            $progress = $this->initiateProgress($user, $bookLesson);
        }

        $this->moveProgressForward($progress);
    }

    public function initiateProgress($user, BookLesson $bookLesson): Progress
    {
        $firstLesson = $this->em->getRepository(Lesson::class)
            ->getFirstLessonOfBookLesson($bookLesson);

        $progress = new Progress();
        $progress->setLesson($firstLesson);
        $progress->setUser($user);
        $progress->setBookLesson($bookLesson);
        $progress->setDifficulty(1);

        $this->em->persist($progress);

        return $progress;
    }

    public function moveProgressForward(Progress $progress): void
    {
        $originalOrder = $progress->getLesson()->getOrder();
        $nextLesson = $this->em->getRepository(Lesson::class)
            ->getNextLessonAfter($progress->getLesson());

        $progress->setLesson($nextLesson);
        $progress->incrementCycleProgression();

        if ($nextLesson->getOrder() < $originalOrder) {
            $progress->incrementDifficulty();
            $progress->setCycleProgression(0);

            if ($progress->getDifficulty() === 5) {
                $progress->setCompleted();
            }
        }
    }

    public function incrementSerieIfNeeded(User $user): void
    {
        $start = (new DateTime())->setTime(0, 0, 0);
        $end = (new DateTime())->setTime(23, 59, 59);

        $lsCountToday = $this->em->getRepository(LearningSession::class)->findAcceptedCountForUserInTimespan(
            $user,
            $start,
            $end
        );

        var_dump($lsCountToday);

        if (!$lsCountToday) {
            $user->incrementCurrentSerie();
        }
    }
}
