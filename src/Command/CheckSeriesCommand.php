<?php
namespace App\Command;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Entity\LearningSession;

class CheckSeriesCommand extends ContainerAwareCommand
{
    private $em;

    protected function configure()
    {
        $this
            ->setName('app:check-series')
            ->setDescription('Check & update series')
            ->setHelp('Check & update series')
            ;
    }

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startOfYesterday = (new DateTime())->modify('-1 day')->setTime(0, 0, 0);
        $endOfYesterday = (new DateTime())->modify('-1 day')->setTime(23, 59, 59);

        $userRepo = $this->em->getRepository(User::class);
        $lsRepo = $this->em->getRepository(LearningSession::class);
        $ids = $userRepo->findAllActiveIds();

        foreach ($ids as $id) {
            $user = $userRepo->findOneById($id);
            $sessionCount = $this->em->getRepository(LearningSession::class)
                ->findCountForUserInTimespan(
                    $user,
                    $startOfYesterday,
                    $endOfYesterday
                );

            $user->setLearningSessionCountThatDay(0);

            if ($sessionCount) {
                $output->writeln(sprintf(
                    '%s : has session, keeping serie',
                    $user
                ));
            } else {
                $output->writeln(sprintf(
                    '%s : has no session, reseting serie',
                    $user
                ));
                $user->setCurrentSerie(0);
            }

            $this->em->flush();
            $this->em->clear();
        }
    }
}
