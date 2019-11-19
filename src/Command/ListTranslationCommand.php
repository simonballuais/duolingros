<?php
namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListTranslationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:translation:list')
            ->setDescription('Liste les Translation')
            ->setHelp('Liste les Translation')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('app.translation_manager');

        $translations = $em->getAll();

        foreach ($translations as $translation) {
            $output->writeln($translation->__toString());
        }
    }
}
