<?php

namespace App\Tests;

use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginConnexionTest extends WebTestCase
{
    public function testLoginWorks(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'yacine',
            '_password' => 'jesuisyacine'
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/admin');

        $client->followRedirect();

        $this->assertSelectorTextContains('.info-container', 'Connecté en tant que yacine');
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'yacine',
            '_password' => 'jenesuispasyacine'
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
            '_username' => 'yacine',
            '_password' => 'jesuisyacine'
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/admin');

        $client->followRedirect();

        $this->assertSelectorTextContains('.info-container', 'Connecté en tant que yacine');

        $client->request('GET', '/logout');

        $this->assertResponseRedirects('http://localhost/');

        $client->followRedirect();

        $this->assertSelectorTextContains('.info-container a', 'Accès administrateur');
    }
}