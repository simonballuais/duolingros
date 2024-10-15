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
use App\Exception\RegistrationFailedException;
use App\Exception\IncorrectLearningSessionSubmissionException;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Uid\Uuid;
use Psr\Log\LoggerInterface;

class RegistrationManager
{
    private $em;
    private $lsm;
    private $um;
    private $logger;

    public function __construct(
        EntityManagerInterface $em,
        LearningSessionManager $learningSessionManager,
        UserManagerInterface $userManager,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->lsm = $learningSessionManager;
        $this->um = $userManager;
        $this->logger = $logger;
    }

    public function submitProfile(
        $email,
        $username,
        $password,
        $reason,
        $currentLevel,
        $dailyObjective,
        $anonymousLearningSessions
    ): User {
        $newUser = $this->um->createUser();
        $newUser->setEmail($email);
        $newUser->setUsername($email);
        $newUser->setNickname($username);
        $newUser->setPlainPassword($password);
        $newUser->setEnabled(true);
        $newUser->setSuperAdmin(false);
        $newUser->setDailyObjective($dailyObjective);
        $newUser->setReason($reason);
        $newUser->setInitialLevel($currentLevel);
        $newUser->setEmailValidationCode(Uuid::v4());
        $this->um->updateUser($newUser);

        $repoLs = $this->em->getRepository(LearningSession::class);

        foreach ($anonymousLearningSessions as $lsData) {
            $ls = $repoLs->findOneById($lsData['learningSessionId']);

            if (!$ls) {
                $this->logger->critical(
                    sprintf(
                        "Could not find anonymous LS %s when validating anomymous LS data. Skipping",
                        $lsData['learningSessionId']
                    )
                );

                continue;
            }

            if (!isset($lsData['validatedAnswers'])) {
                $this->logger->critical("Submitted anonymous LS doesn't have validatedAnswers field. Skipping");

                continue;
            }

            try {
                $ls->setUser($newUser);
                $ls->setStatus(LearningSession::STATUS_STARTED);
                $this->lsm->submit($ls, $lsData['validatedAnswers']);
            } catch (IncorrectLearningSessionSubmissionException $e) {
                $this->logger->critical(
                    sprintf(
                        "An validation error occurred when validating anonymous LS %s : %s. Skipping",
                        $ls->getId(),
                        $e->getMessage()
                    )
                );

                continue;
            }
        }

        $this->em->flush();

        return $newUser;
    }
}
