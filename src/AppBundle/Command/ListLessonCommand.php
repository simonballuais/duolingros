<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListLessonCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:lesson:list')
            ->setDescription('Liste les Lessons')
            ->setHelp('Liste les Lessons')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('app.lesson_manager');

        $lessonList = $em->getAll();

        foreach ($lessonList as $lesson) {
            $output->writeln($lesson->__toString());
        }
    }
}
