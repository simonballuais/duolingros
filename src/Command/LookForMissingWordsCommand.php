<?php
namespace App\Command;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Question\Question;

use App\Entity\Word;
use App\Entity\Translation;

class LookForMissingWordsCommand extends ContainerAwareCommand
{
    private $em;

    protected function configure()
    {
        $this
            ->setName('app:words:check')
            ->setDescription('Check for missing words in translations')
            ->setHelp('Check for missing words in translations')
            ;
    }

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Coucou');
    }
}
