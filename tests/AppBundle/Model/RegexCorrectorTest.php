<?php

namespace Tests\AppBundle\Model;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

use AppBundle\Model\RegexCorrector;
use AppBundle\Model\Proposition;
use AppBundle\Entity\Translation;

class RegexCorrectorTest extends TestCase
{
    private $logger;

    public function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testRightAnswers()
    {
        $corrector = $this->getRegexCorrector();

        $cases =
        [
            [
                "answerList" => [
                    "bonne réponse",
                    "yes c'est goody"
                ],
                "proposition" => new Proposition("Bonne réponse"),
                "expectedRemarks" => [
                ]
            ]
        ];

        foreach ($cases as $case) {
            $translation = new Translation();
            $translation->setAnswerList($case["answerList"]);

            $correction = $corrector->correct(
                $translation,
                $case["proposition"]
            );

            $this->assertEquals(
                $case["expectedRemarks"],
                $correction->getRemarks()
            );
        }
    }

    public function testGenerateCorrectedAnswer()
    {
        $cases =
        [
            [
                "answerList" => [
                    "coincoin"
                ],
                "proposition" => new Proposition(""),
                "expectedRemarks" => [
                    "<strong>coincoin</strong>"
                ]
            ],
            [
                "answerList" => [
                    "coincoin"
                ],
                "proposition" => new Proposition(""),
                "expectedRemarks" => [
                    "<strong>coincoin</strong>"
                ]
            ],
        ];
    }

    public function getRegexCorrector(): RegexCorrector
    {
        return new RegexCorrector(
            $this->logger
        );
    }
}
