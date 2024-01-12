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
       $auNumber = 'SA99700';
       $client = static::createClient();

       $au = $this->createAu($client,$auNumber);

       $crawler = $client->request('GET','/removeAcquisitionUnit');
       $this->assertEquals(200, $client->getResponse()->getStatusCode());


       $form = $crawler->selectButton('Supprimer')->form();

       // I complete the form
       $form->setValues(array(
           'remove_acquisition_unit_form[name]' => $au->getId(),
       ));

       $client->submit($form);
       $client->followRedirect();
       $this->assertStringContainsString($auNumber . ' a bien été supprimé.'
           , $client->getResponse()->getContent(), 'Le flash message apparait ?');
       $client->request('GET', '/addAcquisitionUnit');
       $this->assertEquals(200, $client->getResponse()->getStatusCode());
       $this->assertStringNotContainsString($auNumber, $client->getResponse()->getContent(), 'Le au n est plus visible par le client ?');
   }
   private function createAu($client, $saNumber) : AcquisitionUnit
   {
       $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

       $newSa = new AcquisitionUnit();
       $newSa->setName($saNumber);
       $newSa->setState(AcquisitionUnitState::ATTENTE_AFFECTATION->value);

       $entityManager->persist($newSa);
       $entityManager->flush();

       return $newSa;
   }
}
