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
}
?>
