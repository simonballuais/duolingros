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
    protected $feBaseUrl;

    public function __construct(MailerInterface $mailer, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->feBaseUrl = "https://mitenygasy.com";
    }

    public function sendRegistrationConfirmation(User $user)
    {
        $this->mailer->send((new TemplatedEmail())
            ->to(new Address($user->getEmail()))
            ->from(new Address('noreply@mitenygasy.com', 'Miteny Gasy'))
            ->subject('Confirmez votre adresse email')
            ->htmlTemplate('email/registration_confirmation.html.twig')
            ->context([
                'user' => $user,
                'confirmationUrl' => $this->feBaseUrl . "/confirmer-email?t=" . $user->getEmailValidationCode(),
            ])
        );

        $this->mailer->send((new TemplatedEmail())
            ->to(new Address($user->getEmail()))
            ->from(new Address('noreply@mitenygasy.com', 'Miteny Gasy'))
            ->subject('New user miteny gasy')
            ->htmlTemplate('email/registration_admin_notification.html.twig')
            ->context([
                'user' => $user,
            ])
        );
    }

    public function sendResetPassword(User $user)
    {
        $this->mailer->send((new TemplatedEmail())
            ->to(new Address($user->getEmail()))
            ->from(new Address('noreply@mitenygasy.com', 'Miteny Gasy'))
            ->subject('Demande de changement de mot de passe')
            ->htmlTemplate('email/reset_password.html.twig')
            ->context([
                'user' => $user,
                'resetUrl' => $this->feBaseUrl . "/reset-password?t=" . $user->getConfirmationToken(),
            ])
        );
    }

    public function sendPasswordChanged(User $user)
    {
        $this->mailer->send((new TemplatedEmail())
            ->to(new Address($user->getEmail()))
            ->from(new Address('noreply@mitenygasy.com', 'Miteny Gasy'))
            ->subject('Mot de passe modifié')
            ->htmlTemplate('email/password_changed.html.twig')
            ->context([
                'user' => $user,
            ])
        );
    }
}
