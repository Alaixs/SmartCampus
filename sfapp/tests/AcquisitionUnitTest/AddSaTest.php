<?php

namespace App\Tests;

use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddSaTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testSubmitValidData()
    {
        $newSa = 'SA9999';
        $client = static::createClient();

        $crawler = $client->request('GET', '/addSA');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        // I complete the form
        $form->setValues(array(
            'add_sa_form[number]' => $newSa,
        ));

        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString($newSa, $client->getResponse()->getContent(), 'ca marche?');


        $this->deleteSa($client, $newSa);
    }

    private function deleteSa($client, $saName) : void
    {
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);
        $sa = $acquisitionUnitRepository->findOneBy(array('number' => $saName));
        if ($sa) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($sa);
            $entityManager->flush();
        }
    }
    private function createSa($client, $saNumber) : void
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSa = new AcquisitionUnit();
        $newSa->setNumber($saNumber);
        $newSa->setState(StateSA::ATTENTE_AFFECTATION->value);

        $entityManager->persist($newSa);
        $entityManager->flush();
    }


}