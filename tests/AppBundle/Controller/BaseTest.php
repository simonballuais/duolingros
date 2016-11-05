<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class BaseTestCase extends WebTestCase
{
    protected $client;
    protected $crawler;

    public function assertStringsArePresent($strings)
    {
        foreach ($strings as $string) {
            $this->assertContains($string,
                $this->client->getResponse()->getContent(),
                "{$string} should be displayed");
        }
    }

    public function testShutNoTestFoundWarningUp()
    {
        $this->assertTrue(true);
    }
}
?>
