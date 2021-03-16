<?php
namespace App\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use DateTime;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Lesson;
use App\Entity\LearningSession;
use App\Entity\Translation;
use App\Entity\Question;
use App\Model\Proposition;
use App\Entity\User;
use App\Model\RegexCorrector;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\UserBundle\Model\UserManagerInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/api/password-reset-request",
     *        name="api_request_password_reset",
     *        )
     * @Method({"GET"})
     */
    public function requestPasswordReset(
        Request $request,
        TokenGeneratorInterface $tokenGenerator,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        MailerService $mailer
    ) {
        try {
            $email = json_decode($request->getContent())->email;
        } catch (Exception $e) {
            return new Response(null, 204);
        }

        $user = $userRepository->findOneByEmail($email);

        if (!$user) {
            return new Response(null, 404);
        }

        if (!$user->getConfirmationToken()) {
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $mailer->sendResetPassword($user);
        $user->setPasswordRequestedAt(new DateTime());
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/api/password-change/{token}",
     *        name="api_password_change",
     *        )
     * @Method({"GET"})
     * @Entity("user", expr="repository.findOneByConfirmationToken(token)")
     */
    public function passwordChange(
        Request $request,
        MailerService $mailer,
        User $user,
        UserManagerInterface $userManager
    ) {
        try {
            $json = json_decode($request->getContent());
            $newPassword = $json->password;
        } catch (Exception $e) {
            return new Response('Invalid JSON', 400);
        }

        if (!$newPassword) {
            throw new HttpException(400, 'New password required');
        }

        if ((new DateTime())->modify('-1 hour') > $user->getPasswordRequestedAt()) {
            throw new HttpException(400, 'Token no longer valid');
        }

        $user->setPlainPassword($newPassword);
        $userManager->updateUser($user);
        $mailer->sendPasswordChanged($user);
        $user->setPasswordRequestedAt(new DateTime());

        return new Response(null, 204);
    }
}

