<?php
namespace App\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function submitSession(Request $request, LearningSession $learningSession)
    {
        $body = json_decode($request->getContent(), true);

        if (!isset($body['proposedAnswers'])) {
            return new Response("No proposedAnswers field found", 400);
        }

        $proposedAnswers = $body['proposedAnswers'];

        try {
            $this->lsm->submit($learningSession, $proposedAnswers);
        } catch (IncorrectLearningSessionSubmissionException $e) {
            return new Response($e->getMessage(), 400);
        }

        return new Response(null, 201);
    }
}

