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
//        $saNumber = 'SA99700';
//        $client = static::createClient();
//
//        $this->createSa($client,$saNumber);
//
//        $crawler = $client->request('GET','/removeSA');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//
//
//
//        $form = $crawler->selectButton('Supprimer')->form();
//
//
//        $saRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);
//        $sa = $saRepository->findOneBy(array('number' => $saNumber));
//
//        // I complete the form
//        $form->setValues(array(
//            'remove_sa_form[number]' => $sa->getId(),
//        ));
//
//        $client->submit($form);
//        $client->request('GET', '/addSA');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertStringNotContainsString($saNumber, $client->getResponse()->getContent(), 'ca marche?');
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
