<?php

namespace App\Tests\TechnicienTest\RoomTest\RoomTest\RoomTest\LoginTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginConnexionTest extends WebTestCase
{
    public function testLoginWorks(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'référent',
            '_password' => 'jesuisreferent'
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/admin');

        $client->followRedirect();

        $this->assertSelectorTextContains('.info-container', 'Connecté en tant que référent');
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'référent',
            '_password' => 'jenesuispasréférent'
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');

        $client->followRedirect();

        $this->assertSelectorTextContains('.loginCard .error-message', 'Identifiant ou mot de passe incorrect');

    }

    public function testLogoutWorks(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'référent',
            '_password' => 'jesuisreferent'
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/admin');

        $client->followRedirect();

        $this->assertSelectorTextContains('.info-container', 'Connecté en tant que référent');

        $client->request('GET', '/logout');

        $this->assertResponseRedirects('http://localhost/');

        $client->followRedirect();

        $this->assertSelectorTextContains('.info-container a', 'Accès administrateur');
    }
}