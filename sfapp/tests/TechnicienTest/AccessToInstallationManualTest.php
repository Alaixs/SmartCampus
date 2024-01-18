<?php

namespace App\Tests\TechnicienTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccessToInstallationManualTest extends WebTestCase
{
    /**
     * La méthode testTechnicianHasAccessToInstallationManual() vérifie si une fois connecté,
     * le technicien peut voir le texte "Consulter le manuel d'installation du SA".
     * @return void
     */
    public function testTechnicianHasAccessToInstallationManual()
    {
        $client = static::createClient();

        $this->login($client, 'technicien', 'jesuistechnicien');

        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->filter('footer .installation-manual a:contains("Consulter le manuel d\'installation du SA")')->count();
        $this->assertGreaterThan(0, $link, 'Le lien <a> dans le footer est présent à l\'écran pour le technicien.');
    }

    /**
     * La méthode testMissionHeadHasNotAccessToInstallationManual() vérifie si une fois connecté,
     * le chargé de mission ne peut pas voir pas le texte "Consulter le manuel d'installation du SA".
     * @return void
     */
    public function testMissionHeadHasNotAccessToInstallationManual()
    {
        $client = static::createClient();

        $this->login($client, 'yacine', 'jesuisyacine');

        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->filter('footer .installation-manual a:contains("Consulter le manuel d\'installation du SA")')->count();
        $this->assertEquals(0, $link, 'Le lien <a> dans le footer n\'est présent à l\'écran pour le chargé de mission.');
    }

    /**
     * La méthode testNotLoggedUserHasNotAccessToInstallationManual() vérifie si un usager lambda qui
     * ne se connecte pas, ne peut pas voir le texte "Consulter le manuel d'installation du SA".
     * @return void
     */
    public function testNotLoggedUserHasNotAccessToInstallationManual()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->filter('footer .installation-manual a:contains("Consulter le manuel d\'installation du SA")')->count();
        $this->assertEquals(0, $link, 'Le lien <a> dans le footer ne devrait pas être présent à l\'écran sans connexion.');
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