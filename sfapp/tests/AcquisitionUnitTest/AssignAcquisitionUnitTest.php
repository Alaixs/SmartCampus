<?php

namespace App\Tests;

use App\Domain\StateSA;
use App\Entity\Room;
use App\Repository\AcquisitionUnitRepository;
use App\Entity\AcquisitionUnit;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class AssignAcquisitionUnitTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testSubmitValidData()
    {
        $saNumber = 'SA9999';
        $roomName = 'D999';

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'yacine'));
        $client->loginUser($testUser);

        $this->createRoom($client, $roomName);
        $this->createSa($client, $saNumber);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));
        $sa = $acquisitionUnitRepository->findOneBy(array('number' => $saNumber));

        $crawler = $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Affecter un S.A');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Affecter')->form();

        //     I complete the form
        $form->setValues(array('assign_sa_form[SA]' => $sa->getId()));

        $client->submit($form);
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString($saNumber, $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
        $this->deleteSa($client, $saNumber);
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

    private function createRoom($client, $roomName) : void
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newRoom = new Room();
        $newRoom->setName($roomName);
        $newRoom->setArea(10);
        $newRoom->setFloor(2);
        $newRoom->setExposure('Nord');
        $newRoom->setCapacity(20);
        $newRoom->setHasComputers(0);
        $newRoom->setNbWindows(4);

        $entityManager->persist($newRoom);
        $entityManager->flush();
    }

    private function deleteRoom($client, $roomName) : void
    {
        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(array('name' => $roomName));
        if ($room) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($room);
            $entityManager->flush();
        }
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

}
