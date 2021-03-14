<?php

namespace App\Service;

use DateTime;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\RouterInterface;

class MailerService
{
    protected $mailer;
    protected $router;

    public function __construct(MailerInterface $mailer, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function sendRegistrationConfirmation(User $user)
    {
        $email = (new TemplatedEmail())
            ->to(new Address($user->getEmail()))
            ->subject('Confirmez votre adresse email')
            ->htmlTemplate('email/registration_confirmation.html.twig')
            ->context([
                'user' => $user,
                'confirmationUrl' => "http://coincoin.me:8080/#/confirm-email?t=" . $user->getEmailValidationCode(),
            ])
        ;

        $this->mailer->send($email);
    }
}
