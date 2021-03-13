<?php
namespace App\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializationContext;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Lesson;
use App\Entity\LearningSession;
use App\Entity\Translation;
use App\Entity\Question;
use App\Model\Proposition;
use App\Entity\User;
use App\Model\RegexCorrector;
use App\Manager\RegistrationManager;
use App\Exception\RegistrationFailedException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
    public function submitProfile(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        SerializerInterface $serializer
    ) {
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
            $user = $this->rm->submitProfile(
                $email,
                $username,
                $password,
                $reason,
                $currentLevel,
                $dailyObjective,
                $anonymousLearningSessions
            );

            $jwtToken = $tokenManager->create($user);
        } catch (RegistrationFailedException $e) {
            throw new HttpException(400, 'Couldnt proccess the infos');
        }

        $userJson = $serializer->serialize(
            $user,
            'json',
            ['groups' => 'security']
        );

        return new JsonResponse([
            'user' => json_decode($userJson),
            'token' => $jwtToken,
        ]);
    }
}

