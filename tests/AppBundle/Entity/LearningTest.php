<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use AppBundle\Entity\Learning;


class LeaningTest extends WebTestCase
{
    public function testHotness()
    {
        $cases = [
            [
                "lastPractice" => 1,
                "vacationDays" => 0,
                "hotness" => 3
            ],
            [
                "lastPractice" => 1,
                "vacationDays" => 3,
                "hotness" => 3
            ],
            [
                "lastPractice" => 6,
                "vacationDays" => 1,
                "hotness" => 1
            ],
            [
                "lastPractice" => 8,
                "vacationDays" => 1,
                "hotness" => 0
            ],
            [
                "lastPractice" => 8,
                "vacationDays" => 7,
                "hotness" => 3
            ],
        ];

        foreach ($cases as $case) {
            $lastPractice = new \DateTime();
            $lastPractice->modify("-" . $case["lastPractice"] . " days");

            $learning = new Learning();
            $learning->setLastPractice($lastPractice);
            $learning->setVacationDays($case["vacationDays"]);

            $hotness = $learning->getHotness();

            $this->assertEquals(
                $case['hotness'],
                $hotness,
                sprintf('La last date de %s jours et il y avait une vacation de %s. La hotness attendue est %s',
                    $case['lastPractice'],
                    $case['vacationDays'],
                    $case['hotness']
                )
            );
        }
    }

    public function testLastScores()
    {
        $learning = new Learning();
        $learning->recordScore(1);

        $this->assertEquals(1, $learning->getLastScore());
        $this->assertEquals([1], $learning->getLastScores());

        $learning->recordScore(2);
        $learning->recordScore(3);

        $this->assertEquals(3, $learning->getLastScore());
        $this->assertEquals([1, 2, 3], $learning->getLastScores());

        $learning->recordScore(4);
        $learning->recordScore(5);

        $this->assertEquals(5, $learning->getLastScore());
        $this->assertEquals([3, 4, 5], $learning->getLastScores());
    }

    public function testMastery()
    {
        $learning = new Learning();

        $this->assertEquals(null, $learning->getMastery());

        $learning->recordScore(1);

        $this->assertEquals(1, $learning->getMastery());

        $learning->recordScore(2);
        $learning->recordScore(3);

        $this->assertEquals(2, $learning->getMastery());

        $learning->recordScore(40);
        $learning->recordScore(53);

        $this->assertEquals(32, $learning->getMastery());
    }
}
?>
