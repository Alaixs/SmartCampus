<?php

namespace App\Tests;

use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class AddAcquisitionUnitTest extends WebTestCase
{
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

        $crawler = $client->request('GET', '/addSA');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

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