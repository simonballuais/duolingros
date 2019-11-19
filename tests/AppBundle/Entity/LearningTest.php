<?php
namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Learning;


class LeaningTest extends WebTestCase
{
    public function testHotness()
    {
        $cases = [
            [
                "lastPractice" => 1,
                "lastScores" => [90, 90, 90],
                "hotness" => 3
            ],
            [
                "lastPractice" => 1,
                "lastScores" => [70, 70, 70],
                "hotness" => 3
            ],
            [
                "lastPractice" => 1,
                "lastScores" => [50, 50, 50],
                "hotness" => 2
            ],
            [
                "lastPractice" => 1,
                "lastScores" => [10, 50, 90],
                "hotness" => 2
            ],
            [
                "lastPractice" => 2,
                "lastScores" => [90, 90, 90],
                "hotness" => 3
            ],
            [
                "lastPractice" => 2,
                "lastScores" => [70, 70, 70],
                "hotness" => 2
            ],
            [
                "lastPractice" => 2,
                "lastScores" => [50, 50, 50],
                "hotness" => 1
            ],
            [
                "lastPractice" => 2,
                "lastScores" => [10, 50, 90],
                "hotness" => 1
            ],
            [
                "lastPractice" => 3,
                "lastScores" => [90, 90, 90],
                "hotness" => 3
            ],
            [
                "lastPractice" => 3,
                "lastScores" => [70, 70, 70],
                "hotness" => 2
            ],
            [
                "lastPractice" => 3,
                "lastScores" => [50, 50, 50],
                "hotness" => 1
            ],
            [
                "lastPractice" => 3,
                "lastScores" => [10, 50, 90],
                "hotness" => 1
            ],
            [
                "lastPractice" => 10,
                "lastScores" => [90, 90, 90],
                "hotness" => 2
            ],
            [
                "lastPractice" => 10,
                "lastScores" => [70, 70, 70],
                "hotness" => 1
            ],
            [
                "lastPractice" => 10,
                "lastScores" => [50, 50, 50],
                "hotness" => 1
            ],
            [
                "lastPractice" => 10,
                "lastScores" => [10, 50, 90],
                "hotness" => 1
            ],
            [
                "lastPractice" => 20,
                "lastScores" => [90, 90, 90],
                "hotness" => 1
            ],
            [
                "lastPractice" => 20,
                "lastScores" => [70, 70, 70],
                "hotness" => 1
            ],
            [
                "lastPractice" => 20,
                "lastScores" => [50, 50, 50],
                "hotness" => 1
            ],
            [
                "lastPractice" => 20,
                "lastScores" => [10, 50, 90],
                "hotness" => 1
            ],
        ];

        foreach ($cases as $case) {
            $lastPractice = new \DateTime();
            $lastPractice->modify("-" . $case["lastPractice"] . " days");
            $lastPractice->modify('-5 minute');

            $learning = new Learning();
            $learning->setLastPractice($lastPractice);
            $learning->setLastScores($case["lastScores"]);

            $hotness = $learning->getHotness();

            $this->assertEquals(
                $case['hotness'],
                $hotness,
                sprintf('La last date de %s jours et il y avait des lastScores de %s. La hotness attendue est %s. vacationDays : %s',
                    $case['lastPractice'],
                    implode(', ', $case['lastScores']),
                    $case['hotness'],
                    $learning->getVacationDays()
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
