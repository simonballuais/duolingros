<?php

namespace App\Command;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class SendTestEmailCommand extends ContainerAwareCommand
{
    private MailerService $mailer;

    protected function configure()
    {
        $this
            ->setName('app:email:send-test')
            ->setDescription('Send test email')
            ->setHelp('Send test email');
    }

    public function __construct(MailerService $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Sending email');
        $this->mailer->sendTestEmail();
        $output->writeln('Done');
    }
}
