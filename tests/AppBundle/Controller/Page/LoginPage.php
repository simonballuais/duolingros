<?php
namespace Tests\AppBundle\Controller\Page;


class LoginPage extends BasePage
{
    public function __construct($client)
    {
        $this->client = $client;
        $this->crawler = $this->client->request('GET', '/login');

        return $this;
    }

    public function logInAs()
    {
		// TODO
		/*
        $form = $this->crawler->selectButton('Connexion')->form();
        $form['_username'] = 'NationalAdmin';
        $form['_password'] = 'admin';

        $this->crawler = $this->client->submit($form);
        $this->crawler = $this->client->followRedirect();

        return new LobbyPage($this->client, $this->crawler);
		*/
    }
}
?>
