<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccessToInstallationManualTest extends WebTestCase
{
    public function testFooterLinkIsPresentForTechnician()
    {
        $client = static::createClient();

        $this->login($client, 'technicien', 'jesuistechnicien');

        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->filter('footer .installation-manual a:contains("Télécharger le manuel d\'installation du SA")')->count();
        $this->assertGreaterThan(0, $link, 'Le lien <a> dans le footer est présent à l\'écran pour le technicien.');
    }

    private function login($client, $username, $password)
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