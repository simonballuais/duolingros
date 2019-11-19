<?php

namespace Tests\App\Model;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

use App\Model\RegexCorrector;
use App\Model\Proposition;
use App\Entity\Translation;

class RegexCorrectorTest extends TestCase
{
    private $logger;

    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * @dataProvider provideRightAnswersCases
     * @dataProvider provideRightAnswersCases
     */
    public function testRightAnswers($answerList, $proposition, $expectedRemarks)
    {
        $corrector = $this->getRegexCorrector();

        $translation = new Translation();
        $translation->setAnswerList($answerList);

        $correction = $corrector->correct(
            $translation,
            $proposition
        );

        $this->assertEquals(
            $expectedRemarks,
            $correction->getRemarks()
        );
    }

    public function provideRightAnswersCases(): array
    {
        return [
            'changing case' => [
                "answerList" => [
                    "bonne réponse",
                    "yes c'est goody"
                ],
                "proposition" => new Proposition("Bonne réponse"),
                "expectedRemarks" => [
                ]
            ],
            'completely wrong' => [
                "answerList" => [
                    "coincoin"
                ],
                "proposition" => new Proposition(""),
                "expectedRemarks" => [
                    "Vouliez-vous dire \"<strong>coincoin</strong>\" ?"
                ]
            ],
            [
                "answerList" => [
                    "coincoin"
                ],
                "proposition" => new Proposition(""),
                "expectedRemarks" => [
                    "Vouliez-vous dire \"<strong>coincoin</strong>\" ?"
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
