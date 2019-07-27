<?php
namespace AppBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadTranslationData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    protected $container;

    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('app.translation_manager');
        $lm = $this->container->get('app.lesson_manager');
        $csvDirectory = $this->container->getParameter('csv_directory');

        $file = fopen($csvDirectory . "/translation.csv", "r");

        if (is_null($file)) {
            echo("Impossible de trouver le fichier translation.csv\n");
            return;
        }

        $csvLine = fgetcsv($file, 0, ';');
        $index = 2;

        while($csvLine = fgetcsv($file, 0, ';')) {
            if (!isset($csvLine[1])) {
                echo("Skip line $index\n");
                continue;
            }

            $lesson = $lm->get($csvLine[1]);

            if (null === $lesson) {
                echo("Skip line $index car la Lesson {$csvLine[1]} n'existe pas\n");
                continue;
            }

            $id = $csvLine[0];
            $text = $csvLine[2];
            $answerList = $csvLine;
            array_shift($answerList);
            array_shift($answerList);
            array_shift($answerList);

            $translation = $em->createOrUpdate($text, $answerList, $lesson, $id);

            $index++;
        }

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 2;
    }
}
?>
