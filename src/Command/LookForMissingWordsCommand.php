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
        $output->writeln('Starting words check');
        $helper = $this->getHelper('question');

        $wordRepo = $this->em->getRepository(Word::class);
        $translations = $this->em->getRepository(Translation::class)->findAll();
        $words = $wordRepo->findAllOriginalsSortedByLength();

        foreach ($translations as $translation) {
            foreach ($translation->getConcreteAnswers() as $answer) {
                $subject = strtolower($answer);
                $subject = str_replace(",", " ", $subject);
                $subject = str_replace("(", " ", $subject);
                $subject = str_replace(")", " ", $subject);
                $subject = str_replace("?", " ", $subject);
                var_dump($subject);

                foreach ($words as $word) {
                    $key = $word;
                    $key = str_replace("(", "", $key);
                    $key = str_replace(")", "", $key);

                    $subject = preg_replace(
                        "/(?<=^| )(" . $key . ")(?= |$)/i",
                        "###",
                        $subject
                    );
                }

                if (preg_match("/[a-z]/i", $subject)) {
                    $subject = preg_replace("/#/", " ", $subject);
                    $unknownWords = explode(" ", $subject);

                    foreach ($unknownWords as $unknown) {
                        $unknown = str_replace("(", "", $unknown);
                        $unknown = str_replace(")", "", $unknown);

                        if (!preg_match("/[a-z]/i", $unknown)) {
                            continue;
                        }

                        $question = new Question('"' . $unknown . '" ?: ');
                        $output->writeln($answer);
                        $newTranslation = $helper->ask($input, $output, $question);

                        if (preg_match("/,/", $newTranslation)) {
                            $elems = explode(",", $newTranslation);

                            foreach ($elems as &$e) {
                                $e = trim($e);
                            }

                            $newTranslation = json_decode(json_encode($elems));
                        }

                        $newWord = new Word();
                        $newWord->setKey($unknown);

                        if ($newTranslation) {
                            $newWord->setValue($newTranslation);
                        } else {
                            $newWord->setValue(null);
                        }

                        $this->em->persist($newWord);

                        try {
                            $this->em->flush();
                        } catch (\Exception $e) {
                            $output->writeln($e->getMessage());
                        }

                        $words[] = $newWord->getKey();
                    }
                }
            }
        }

        $this->em->flush();
        $output->writeln('Done');
    }
}
