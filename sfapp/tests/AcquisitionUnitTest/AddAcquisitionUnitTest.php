<?php

namespace App\Tests;

use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddAcquisitionUnitTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testSubmitValidData()
    {
        $newAcquisitionUnit = 'ESP-017';
        $client = static::createClient();

        $crawler = $client->request('GET', '/addSA');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        // I complete the form
        $form->setValues(array(
            'add_sa_form[number]' => $newAcquisitionUnit,
        ));

        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString($newAcquisitionUnit, $client->getResponse()->getContent(), 'ca marche?');


        $this->deleteSa($client, $newAcquisitionUnit);
    }

    private function deleteSa($client, $acquisitionUnitName) : void
    {
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);
        $acquisitionUnit = $acquisitionUnitRepository->findOneBy(array('number' => $acquisitionUnitName));
        if ($acquisitionUnit) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($acquisitionUnit);
            $entityManager->flush();
        }
    }
    private function createSa($client, $saNumber) : void
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newAcquisitionUnit = new AcquisitionUnit();
        $newAcquisitionUnit->setName($saNumber);
        $newAcquisitionUnit->setState(AcquisitionUnitState::ATTENTE_AFFECTATION->value);

        $entityManager->persist($newAcquisitionUnit);
        $entityManager->flush();
    }


}