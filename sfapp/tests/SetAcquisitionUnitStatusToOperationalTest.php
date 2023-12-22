<?php
//
//namespace App\Tests;
//
//use App\Entity\AcquisitionUnit;
//use App\Repository\AcquisitionUnitRepository;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//
//class SetAcquisitionUnitStatusToOperationalTest extends WebTestCase
//{
//    public function testSetAcquisitionUnitStatusToOperational()
//    {
//        $client = static::createClient();
//        $entityManager = $client->getContainer()->get('doctrine')->getManager();
//
//        // Récupérer l'objet AcquisitionUnit $SA1000 de vos fixtures
//        $saNumber = 'SA1000';
//        $SA1000 = $entityManager->getRepository(AcquisitionUnit::class)->findOneBy(['number' => $saNumber]);
//
//        // Assurez-vous que $SA1000 est récupéré avec succès
//        $this->assertInstanceOf(AcquisitionUnit::class, $SA1000);
//
//        // Fait la requête vers la route
//        $crawler = $client->request('GET', '/defSAoperationnel/{idSA}' . $saNumber);
//
//        // Vérifie si le code de statut de la réponse est 200
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }
//}
//
