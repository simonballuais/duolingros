<?php
namespace App\Listener\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\Serializer\SerializerInterface;

class JWTAuthenticationSuccessListener implements EventSubscriberInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::AUTHENTICATION_SUCCESS => 'handleJWTAuthenticationSuccess',
        ];
    }

    public function handleJWTAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $jwt = $event->getData()['token'];
        $user = $event->getUser();
        $response = $event->getResponse();

        $userJson = $this->serializer->serialize(
            $user,
            'json',
            ['groups' => 'security']
        );

        $event->setData([
            'user' => json_decode($userJson),
            'token' => $jwt,
        ]);

        return $response;
    }
}
