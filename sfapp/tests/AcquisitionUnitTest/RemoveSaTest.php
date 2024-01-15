<?php

namespace App\Tests;

use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RemoveSaTest extends WebTestCase
{
   /**
    * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
    * @return void
    */
   public function testSubmitValidData()
   {
       $saNumber = 'SA99700';
       $client = static::createClient();

       $this->createSa($client,$saNumber);

       $crawler = $client->request('GET','/removeAcquisitionUnit');
       $this->assertEquals(200, $client->getResponse()->getStatusCode());


       $form = $crawler->selectButton('Supprimer')->form();


       $saRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);
       $sa = $saRepository->findOneBy(array('name' => $saNumber));

       // I complete the form
       $form->setValues(array(
           'remove_acquisition_unit_form[name]' => $sa->getId(),
       ));

       $client->submit($form);
       $client->request('GET', '/addAcquisitionUnit');
       $this->assertEquals(200, $client->getResponse()->getStatusCode());
       $client->reload();
       $this->assertStringNotContainsString($saNumber, $client->getResponse()->getContent(), 'ca marche?');
   }
   private function createSa($client, $saNumber) : void
   {
       $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

       $newSa = new AcquisitionUnit();
       $newSa->setName($saNumber);
       $newSa->setState(AcquisitionUnitState::ATTENTE_AFFECTATION->value);

       $entityManager->persist($newSa);
       $entityManager->flush();
   }
}
