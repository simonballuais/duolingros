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
use App\Manager\RegistrationManager;
use App\Exception\RegistrationFailedException;

class RegistrationController extends Controller
{
    private $rm;

    public function __construct(RegistrationManager $rm)
    {
        $this->rm = $rm;
    }

    /**
     * @Route("/api/submit-profile",
     *        name="api_submit_profile",
     *        )
     * @Method({"POST"})
     */
    public function submitProfile(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        if (!$body) {
            return new Response(
                json_encode([
                    'message' => 'Invalid json body',
                ]),
                400
            );
        }

        $email = $body['email'];
        $username = $body['username'];
        $password = $body['password'];
        $dailyObjective = $body['dailyObjective'];
        $reason = $body['reason'];
        $currentLevel = $body['currentLevel'];

        if (!$email || !$password || !$dailyObjective) {
            return new Response(
                json_encode([
                    'message' => 'Invalid json body',
                ]),
                400
            );
        }

        if ($this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email])) {
            return new Response(
                json_encode([
                    'message' => 'Cet email est déjà utilisé',
                    'field' => 'email',
                ]),
                400
            );
        }

        $anonymousLearningSessions = $body['anonymousLearningSessions'];

        try {
            $this->rm->submitProfile(
                $email,
                $username,
                $password,
                $reason,
                $currentLevel,
                $dailyObjective,
                $anonymousLearningSessions
            );
        } catch (RegistrationFailedException $e) {
            
        }

        return new Response(
            'oki', // todo: login token
            200
        );
    }
}

