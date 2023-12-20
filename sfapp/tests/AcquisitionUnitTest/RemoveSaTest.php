<?php
//
//namespace App\Tests;
//
//use App\Domain\StateSA;
//use App\Entity\AcquisitionUnit;
//use App\Repository\AcquisitionUnitRepository;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//
//class RemoveSaTest extends WebTestCase
//{
//    /**
//     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
//     * @return void
//     */
//    public function testSubmitValidData()
//    {
//        $saNumber = 'SA9999';
//        $client = static::createClient();
//        //$this->createSa($client,$saNumber);
//        $crawler = $client->request('GET', '/removeSA/1');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//        $form = $crawler->selectButton('Supprimer')->form();
//        dump($form->getValues());
//        // I complete the form
////        $form->setValues(array(
////            'add_sa_form[number]' => $newSa,
////        ));
////
////        $client->submit($form);
////        $this->assertEquals(200, $client->getResponse()->getStatusCode());
////        $this->assertStringContainsString($newSa, $client->getResponse()->getContent(), 'ca marche?');
//    }
//    private function createSa($client, $saNumber) : void
//    {
//        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
//
//        $newSa = new AcquisitionUnit();
//        $newSa->setNumber($saNumber);
//        $newSa->setState(StateSA::ATTENTE_AFFECTATION->value);
//
//        $entityManager->persist($newSa);
//        $entityManager->flush();
//    }
//}
