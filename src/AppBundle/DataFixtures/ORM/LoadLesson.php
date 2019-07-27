<?php
namespace AppBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadLessonData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    protected $container;

    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('app.lesson_manager');
        $csvDirectory = $this->container->getParameter('csv_directory');

        $file = fopen($csvDirectory . "/lesson.csv", "r");

        if (is_null($file)) {
            echo("Impossible de trouver le fichier lesson.csv");
            return;
        }

        $csvLine = fgetcsv($file, 0, ';');

        while($csvLine = fgetcsv($file, 0, ';')) {
            if (!isset($csvLine[0])) {
                echo("Skip line $index");
                continue;
            }

            $id = $csvLine[0];
            $title = $csvLine[1];
            $bookLessonId = $csvLine[4];

            $lesson = $em->createOrUpdate($title, $bookLessonId, $id);

            $lesson->setTranslationPerStudy($csvLine[2]);
            $lesson->setDescription($csvLine[3]);
        }

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }
}
?>
