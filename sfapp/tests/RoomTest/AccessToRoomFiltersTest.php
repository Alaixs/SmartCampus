<?php

namespace App\Tests\RoomTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class AccessToRoomFiltersTest extends WebTestCase
{
    /**
     * La méthode testTechnicianHasAccessToFilters() vérifie si une fois connecté,
     * le technicien peut voir les filtres des salles.
     * @return void
     */
    public function testTechnicianHasAccessToFilters()
    {
        $client = static::createClient();
        $this->login($client, 'technicien', 'jesuistechnicien');
        $client->request('GET', '/admin');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $html = $client->getResponse()->getContent();
        $crawler = new Crawler($html);

        $this->assertCount(1, $crawler->filter('section.filterForm'));
    }

    /**
     * La méthode testMissionHeadHasAccessToFilters() vérifie si une fois connecté,
     * le chargé de mission peut voir les filtres des salles.
     * @return void
     */
    public function testMissionHeadHasAccessToFilters()
    {
        $client = static::createClient();
        $this->login($client, 'referent', 'jesuisreferent');
        $client->request('GET', '/admin');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $html = $client->getResponse()->getContent();
        $crawler = new Crawler($html);

        $this->assertCount(1, $crawler->filter('section.filterForm'));
    }

    /**
     * La méthode login() connecte un client avec son identifiant et son mot de passe
     * @param $client
     * @param string $username
     * @param string $password
     */
    private function login($client, string $username, string $password): void
    {
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $username,
            '_password' => $password,
        ]);
        $client->submit($form);
        $client->followRedirect();
    }
}