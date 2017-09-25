<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
            $this
                ->setName('app:create-admin')
                ->setDescription('Create admins')
                ->setHelp('Create admins')
                ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $um = $this->getContainer()->get('fos_user.user_manager');

        $admin = $um->findUserByUsername('admin');

        if ($admin) {
            $um->deleteUser($admin);
        }

        $coincoin = $um->findUserByUsername('coincoin');

        if ($coincoin) {
            $um->deleteUser($coincoin);
        }

        $tralala = $um->findUserByUsername('tralala');

        if ($tralala) {
            $um->deleteUser($tralala);
        }

        $roger = $um->findUserByUsername('roger');

        if ($roger) {
            $um->deleteUser($roger);
        }

        $nationalAdmin = $um->findUserByUsername('NationalAdmin');

        if ($nationalAdmin) {
            $um->deleteUser($nationalAdmin);
        }

        $distributorAdmin = $um->findUserByUsername('DistributorAdmin');

        if ($distributorAdmin) {
            $um->deleteUser($distributorAdmin);
        }

        $nationalAdmin = $um->createUser();
        $nationalAdmin->setUsername('NationalAdmin');
        $nationalAdmin->setEmail('na@test.com');
        $nationalAdmin->setPlainPassword('admin');
        $nationalAdmin->setEnabled(True);
        $nationalAdmin->addRole('ROLE_NATIONAL_ADMIN');

        $admin = $um->createUser();
        $admin->setUsername('admin');
        $admin->setEmail('simon.ballu@gmail.com');
        $admin->setPlainPassword('admin');
        $admin->setEnabled(True);
        $admin->addRole('ROLE_ADMIN');

        $coincoin = $um->createUser();
        $coincoin->setUsername('coincoin');
        $coincoin->setEmail('ana@test.com');
        $coincoin->setPlainPassword('admin');
        $coincoin->setEnabled(True);
        $coincoin->addRole('ROLE_NATIONAL_ADMIN');

        $tralala = $um->createUser();
        $tralala->setUsername('tralala');
        $tralala->setEmail('nieauea@test.com');
        $tralala->setPlainPassword('admin');
        $tralala->setEnabled(True);
        $tralala->addRole('ROLE_NATIONAL_ADMIN');

        $roger = $um->createUser();
        $roger->setUsername('roger');
        $roger->setEmail('deauiea@test.com');
        $roger->setPlainPassword('admin');
        $roger->setEnabled(True);
        $roger->addRole('ROLE_DISTRIBUTOR_ADMIN');

        $distributorAdmin = $um->createUser();
        $distributorAdmin->setUsername('DistributorAdmin');
        $distributorAdmin->setEmail('da@test.com');
        $distributorAdmin->setPlainPassword('admin');
        $distributorAdmin->setEnabled(True);
        $distributorAdmin->addRole('ROLE_DISTRIBUTOR_ADMIN');

        $um->updateUser($nationalAdmin);
        $um->updateUser($tralala);
        $um->updateUser($roger);
        $um->updateUser($coincoin);
        $um->updateUser($admin);
        $um->updateUser($distributorAdmin);

        $output->writeln('Admins created');
    }
}
