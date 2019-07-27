<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use AppBundle\Model\Proposition;


class PlayTranslationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:translation:play')
            ->setDescription('Jouer un Translation')
            ->setHelp('Jouer un Translation')
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

        $em = $this->getContainer()->get('app.translation_manager');

        $translations = $em->getAll();

        if (!isset($translations[$index])) {
            $output->writeln("Cet Translation n'existe pas");
            return;
        }

        $translation = $translations[$index];
        $output->writeln($translation->getText());

        $question = new Question("Votre réponse : ");
        $answer = $helper->ask($input, $output, $question);
        $proposition = new Proposition($answer);
        $correction = $translation->treatProposition($proposition);

        if ($correction->isOk()) {
            $output->writeLn("Oki");
        }
        else {
            $output->writeln($correction->getRemarks()[0]);
        }

    }
}
