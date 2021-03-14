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


class RegistrationManager
{
    private $em;
    private $lsm;
    private $um;

    public function __construct(
        EntityManagerInterface $em,
        LearningSessionManager $learningSessionManager,
        UserManagerInterface $userManager
    ) {
        $this->em = $em;
        $this->lsm = $learningSessionManager;
        $this->um = $userManager;
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
        $this->um->updateUser($newUser);

        $repoLs = $this->em->getRepository(LearningSession::class);

        foreach ($anonymousLearningSessions as $lsData) {
            $ls = $repoLs->findOneById($lsData['learningSessionId']);

            if (!$ls) {
                // todo: log properly
                echo("unknown LS\n");
                continue;
            }

            if (!isset($lsData['validatedAnswers'])) {
                // todo: log properly
                echo("cant find answers data\n");
                continue;
            }

            try {
                $ls->setUser($newUser);
                $ls->setStatus(LearningSession::STATUS_STARTED);
                $this->lsm->submit($ls, $lsData['validatedAnswers']);
            } catch (IncorrectLearningSessionSubmissionException $e) {
                // todo: log properly
                echo($ls->getId() . " - " . $e->getMessage());
                continue;
            }
        }

        $this->em->flush();

        return $newUser;
    }
}
