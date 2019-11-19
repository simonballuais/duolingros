<?php

namespace Tests\ApAppler;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tool\StringComparer;

class StringComparerTest extends WebTestCase
{
    public function testDiff()
    {
        $this->assertNotEquals(
            StringComparer::findClosestCandidate(
                "coincoin",
                [
                    "coinncoinnn",
                    "anuitscoin",
                    "coiincoin",
                    "anusrietatnuire"
                ]
            ),
            "coiiniaruiste"
        );

        $this->assertEquals(
            StringComparer::findClosestCandidate(
                "coincoin",
                [
                    "coinncoinnn",
                    "anuitscoin",
                    "coiincoin",
                    "anusrietatnuire"
                ]
            ),
            "coiincoin"
        );
    }
}
