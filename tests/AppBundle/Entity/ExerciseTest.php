<?php
namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Translation;

class TranslationTest extends WebTestCase
{
    public function testConcretise()
    {
        $cases = [
            [
                "answers" => ["J'ai une (carrotte (orange|verte)|orange (pas mûre|pourrie|))"],
                "expectedConcreteAnswers" => [
                    "J'ai une carrotte orange",
                    "J'ai une carrotte verte",
                    "J'ai une orange pas mûre",
                    "J'ai une orange pourrie",
                    "J'ai une orange",
                ]
            ],
            [
                "answers" => ["J'ai (une|deux|trois) grosses (carrottes|oranges)"],
                "expectedConcreteAnswers" => [
                    "J'ai une grosses carrottes",
                    "J'ai deux grosses carrottes",
                    "J'ai trois grosses carrottes",
                    "J'ai une grosses oranges",
                    "J'ai deux grosses oranges",
                    "J'ai trois grosses oranges",
                ]
            ],
            [
                "answers" => ["Une pierre a tué (un (chien (tout kiki|)|cheval (au trot|au gallop))|une (chienne|chatte (qui miaule|qui fait caca dans la douche)))"],
                "expectedConcreteAnswers" => [
                    "Une pierre a tué un chien",
                    "Une pierre a tué un chien tout kiki",
                    "Une pierre a tué un cheval au trot",
                    "Une pierre a tué un cheval au gallop",
                    "Une pierre a tué une chienne",
                    "Une pierre a tué une chatte qui miaule",
                    "Une pierre a tué une chatte qui fait caca dans la douche",
                ]
            ],
            [
                "answers" => ["(qui (est-ce qui est|c'est)|c'est qui qui est) le plus fort"],
                "expectedConcreteAnswers" => [
                    "qui c'est le plus fort",
                    "qui est-ce qui est le plus fort",
                    "c'est qui qui est le plus fort",
                ]
            ],
        ];


        foreach ($cases as $case) {
            $translation = new Translation();
            $translation->setAnswers($case["answers"]);

            $expectedConcreteAnswers = $case["expectedConcreteAnswers"];
            $concreteAnswers = $translation->getConcreteAnswers();

            $this->assertEqualsCanonicalizing(
                $expectedConcreteAnswers,
                $concreteAnswers,
                "Inconsistence de concrétisation de réponse",
                $delta = 0.0,
                $maxDepth = 10,
                $canonicalize = true
            );

        }
    }

    public function testFindNonRecursiveOptionGroups()
    {
        $cases = [
            [
                "candidate" => "test (yolo|un|deux), hey (ou pas)",
                "expectedOptionGroups" => [
                    "(yolo|un|deux)",
                ]
            ],
            [
                "candidate" => "test (yolo|un|deux(coucou|lol)), hey (ou pas)",
                "expectedOptionGroups" => [
                    "(coucou|lol)",
                ]
            ],
            [
                "candidate" => "test (coincoin|tralala|lol) test (yolo|un|deux(coucou|lol)), hey (ou pas|fesse)",
                "expectedOptionGroups" => [
                    "(coucou|lol)",
                    "(ou pas|fesse)",
                    "(coincoin|tralala|lol)",
                ]
            ],
            [
                "candidate" => "test (coincoin|tralala|lol) test (yolo|un|deux(coucou|lol (hey|coincoin (tralala|fesse|prout)))), hey (ou pas|fesse)",
                "expectedOptionGroups" => [
                    "(coincoin|tralala|lol)",
                    "(ou pas|fesse)",
                    "(tralala|fesse|prout)",
                ]
            ],
        ];

        foreach ($cases as $case) {
            $translation = new Translation();

            $expectedOptionGroups = $case["expectedOptionGroups"];
            $concreteOptionGroups = $translation->findNonRecursiveOptionGroups($case["candidate"]);

            $this->assertEqualsCanonicalizing(
                $expectedOptionGroups,
                $concreteOptionGroups,
                "Inconsistence de recherche de groupe d'options",
                $delta = 0.0,
                $maxDepth = 10,
                $canonicalize = true
            );
        }
    }
}
?>
