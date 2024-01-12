<?php

namespace App\Tests;

use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class AddAcquisitionUnitTest extends WebTestCase
{

    public static function setUpBeforeClass(): void
    {
        exec('php bin/console doctrine:fixtures:load --env=test --quiet');
    }
    
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testSubmitValidData()
    {
        $newSa = 'SA9999';

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'yacine'));
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/addAcquisitionUnit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues(array(
            'add_acquisition_unit_form[name]' => $newSa,
        ));

        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertStringContainsString($newSa, $client->getResponse()->getContent(), 'ca marche?');


        $this->deleteSa($client, $newSa);
    }

    private function deleteSa($client, $saName) : void
    {
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);
        $sa = $acquisitionUnitRepository->findOneBy(array('name' => $saName));
        if ($sa) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($sa);
            $entityManager->flush();
        }
    }

}
