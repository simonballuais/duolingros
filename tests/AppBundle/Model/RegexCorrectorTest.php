<?php

namespace Tests\AppBundle\Model;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use AppBundle\Model\Proposition;
use AppBundle\Entity\Translation;

class RegexCorrectorTest extends WebTestCase
{
    public function testRightAnswers()
    {
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
            $correction = $translation->treatProposition($case["proposition"]);

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
}
