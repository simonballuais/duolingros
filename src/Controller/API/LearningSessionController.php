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
use App\Entity\Translation;
use App\Entity\Question;
use App\Model\Proposition;
use App\Entity\User;
use App\Model\RegexCorrector;
use App\Manager\LearningSessionManager;

class LearningSessionController extends Controller
{
    private $lsm;

    public function __construct(LearningSessionManager $lsm)
    {
        $this->lsm = $lsm;
    }

    /**
     * @Route("/api/learning-session/start-lesson/{lesson}/{difficulty}",
     *        name="api_start_learning_session",
     *        options={"expose"=true}
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
}

