<?php
namespace AppBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadExerciseData implements FixtureInterface, ContainerAwareInterface
{
    protected $container;

    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('app.exercise_manager');
        $csvDirectory = $this->container->getParameter('csv_directory');

        $file = fopen($csvDirectory . "/exercise.csv", "r");

        if (is_null($file)) {
            echo("Impossible de trouver le fichier exercise.csv");
            return;
        }

        while($csvLine = fgetcsv($file, 0, ';')) {
            if (!isset($csvLine[0])) {
                echo("Skip line $index");
                continue;
            }

            $text = $csvLine[0];
            $answerList = $csvLine;
            array_shift($answerList);

            $em->createOrUpdate($text, $answerList);
        }

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
?>
