<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use AppBundle\Model\Proposition;


class PlayExerciseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:exercise:play')
            ->setDescription('Jouer un Exercise')
            ->setHelp('Jouer un Exercise')
            ->setDefinition(
                new InputDefinition([
                        new InputOption('index', 'i', InputOption::VALUE_REQUIRED),
                    ])
                )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $index = intval($input->getOption('index'));

        $em = $this->getContainer()->get('app.exercise_manager');

        $exercises = $em->getAll();

        if (!isset($exercises[$index])) {
            $output->writeln("Cet Exercise n'existe pas");
            return;
        }

        $exercise = $exercises[$index];
        $output->writeln($exercise->getText());

        $question = new Question("Votre réponse");
        $answer = $helper->ask($input, $output, $question);
        $proposition = new Proposition($answer);
        $correction = $exercise->treatProposition($proposition);

        if ($correction->isOk()) {
            $output->writeLn("Oki");
        }
        else {
            $output->writeln($correction->getRemarks()[0]);
        }

    }
}
