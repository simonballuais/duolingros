<?php
namespace App\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\MailerService;

use JMS\Serializer\SerializationContext;
use App\Entity\Lesson;
use App\Entity\LearningSession;
use App\Entity\Translation;
use App\Entity\Question;
use App\Model\Proposition;
use App\Entity\User;
use App\Model\RegexCorrector;
use App\Manager\LearningSessionManager;
use App\Exception\IncorrectLearningSessionSubmissionException;

class LearningSessionController extends Controller
{
    private $lsm;

    public function __construct(LearningSessionManager $lsm)
    {
        $this->lsm = $lsm;
    }

    /**
     * @Route("/api/learning-sessions/start-lesson/{lesson}/{difficulty}",
     *        name="api_start_learning_session",
     *        )
     * @Method({"POST"})
     */
    public function startSession(Lesson $lesson, $difficulty)
    {
        $user = $this->getUser();
        $ls = $this->lsm->start($user, $lesson, $difficulty);
        $context = SerializationContext::create()->setGroups(['startLearningSession']);

        $serializer = $this->get('jms_serializer');
        $ls = json_decode($serializer->serialize($ls, 'json', $context));

        return new JsonResponse($ls);
    }

    /**
     * @Route("/api/learning-sessions/{learningSession}/submit",
     *        name="api_submit_learning_session",
     *        )
     * @Method({"POST"})
     */
    public function submitSession(
        Request $request,
        LearningSession $learningSession,
        MailerService $mailer
    ) {
        $body = json_decode($request->getContent(), true);

        if (!isset($body['proposedAnswers'])) {
            return new Response("No proposedAnswers field found", 400);
        }

        $proposedAnswers = $body['proposedAnswers'];

        try {
            $this->lsm->submit($learningSession, $proposedAnswers);
        } catch (IncorrectLearningSessionSubmissionException $e) {
            $mailer->sendSubmissionError($e->getMessage());
        }

        return new Response(null, 201);
    }

    /**
     * @Route("/api/last-seven-days-graph",
     *        name="api_last_seven_days_graph",
     *        )
     * @Method({"GET"})
     */
    public function getLastSevenDaysGraph()
    {
        $data = $this->getDoctrine()->getEntityManager()->getRepository(LearningSession::class)
            ->getLastSevenDaysCountsForUser($this->getUser());

        $formattedResults = [];

        foreach ($data as $dayKey => $count) {
            $formattedResults[] = [
                'day' => $dayKey,
                'count' => $count,
            ];
        }

        return new JsonResponse($formattedResults);
    }

    /**
     * @Route("/api/anonymous-learning-sessions/start-lesson/{lesson}/{difficulty}",
     *        name="api_start_anonymous_learning_session",
     *        )
     * @Method({"POST"})
     */
    public function startAnonymousSession(Lesson $lesson, $difficulty)
    {
        $ls = $this->lsm->startAnonymous($lesson, $difficulty);
        $context = SerializationContext::create()->setGroups(['startLearningSession']);

        $serializer = $this->get('jms_serializer');
        $ls = json_decode($serializer->serialize($ls, 'json', $context));

        return new JsonResponse($ls);
    }

    /**
     * @Route("/api/anonymous-learning-sessions/{learningSession}/submit",
     *        name="api_submit_anonymous_learning_session",
     *        )
     * @Method({"POST"})
     */
    public function submitAnonymousSession(
        Request $request,
        LearningSession $learningSession,
        MailerService $mailer
    ) {
        $body = json_decode($request->getContent(), true);

        if (!isset($body['proposedAnswers'])) {
            return new Response("No proposedAnswers field found", 400);
        }

        $proposedAnswers = $body['proposedAnswers'];

        try {
            $this->lsm->submitAnonymousSession($learningSession, $proposedAnswers);
        } catch (IncorrectLearningSessionSubmissionException $e) {
            $mailer->sendSubmissionError($e->getMessage());
        }

        return new Response(null, 201);
    }
}

