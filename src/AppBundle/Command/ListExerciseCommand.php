<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListExerciseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:exercise:list')
            ->setDescription('Liste les Exercise')
            ->setHelp('Liste les Exercise')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('app.exercise_manager');

        $exercises = $em->getAll();

        foreach ($exercises as $exercise) {
            $output->writeln($exercise->__toString());
        }
    }
}
