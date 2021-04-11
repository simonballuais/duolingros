<?php
namespace App\Command;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Word;
use App\Entity\Translation;

class UpdateTranslationsWordsCommand extends ContainerAwareCommand
{
    private $em;

    protected function configure()
    {
        $this
            ->setName('app:words:update')
            ->setDescription('Update all translations word field from Word entities')
            ->setHelp('Update all translations word field from Word entities')
            ;
    }

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting words update');

        $wordRepo = $this->em->getRepository(Word::class);
        $translations = $this->em->getRepository(Translation::class)->findAll();

        foreach ($translations as $translation) {
            $words = [];
            $wordElements = explode(" ", $this->purifyString($translation->getText()));

            foreach ($wordElements as $element) {
                $word = $wordRepo->findOneByKey(strtolower($element));

                if ($word) {
                    $wordValue = $word->getValue();

                    if (!is_array($wordValue)) {
                        $wordValue = [$wordValue];
                    }

                    $words[$element] = $wordValue;
                }
            }

            $translation->setWords($words);
        }

        $this->em->flush();
        $output->writeln('Done');
    }

    public function purifyString($string)
    {
        $purifiedString = preg_replace('/[.,:\-;?\']/', ' ', $string);
        $purifiedString = strtolower($purifiedString);
        $purifiedString = preg_replace('/  /', ' ', $purifiedString);
        $purifiedString = preg_replace('/  /', ' ', $purifiedString);
        $purifiedString = preg_replace('/  /', ' ', $purifiedString);
        $purifiedString = preg_replace('/  /', ' ', $purifiedString);
        $purifiedString = preg_replace('/^/', '', $purifiedString);
        $purifiedString = preg_replace('/[éèê]/', 'e', $purifiedString);
        $purifiedString = preg_replace('/[àâ]/', 'a', $purifiedString);
        $purifiedString = preg_replace('/[îï]/', 'i', $purifiedString);
        $purifiedString = preg_replace('/[îï]/', 'i', $purifiedString);
        $purifiedString = trim($purifiedString);

        return $purifiedString;
    }
}
