<?php
namespace Tests\AppBundle\Controller\Page;


class BasePage
{
    public $client;
    public $crawler;

    public function __construct($client, $crawler)
    {
        $this->client = $client;
        $this->crawler = $crawler;
    }
}
?>
