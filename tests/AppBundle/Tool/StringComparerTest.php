<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Tool\StringComparer;

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
