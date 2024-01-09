<?php

namespace App\Tests;

use App\Domain\AcquisitionUnitState;
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
        $this->createAcquisitionUnit($client, $acquisitionUnitName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));
        $acquisitionUnit = $acquisitionUnitRepository->findOneBy(array('number' => $acquisitionUnitName));

        $crawler = $client->request('GET', '/roomDetail/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/roomDetail/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Affecter un SA');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Affecter')->form();

        // I complete the form
        $form->setValues(array('assign_sa_form[SA]' => $acquisitionUnit->getId()));

        $client->submit($form);
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString($acquisitionUnitName, $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
        $this->deleteAcquisitionUnit($client, $acquisitionUnitName);
    }



    private function createAcquisitionUnit($client, $acquisitionUnitName) : void
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newAcquisitionUnit = new AcquisitionUnit();
        $newAcquisitionUnit->setName($acquisitionUnitName);
        $newAcquisitionUnit->setState(AcquisitionUnitState::ATTENTE_AFFECTATION->value);

        $entityManager->persist($newAcquisitionUnit);
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
    private function deleteAcquisitionUnit($client, $acquisitionUnitName) : void
    {
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);
        $acquisitionUnit = $acquisitionUnitRepository->findOneBy(array('number' => $acquisitionUnitName));
        if ($acquisitionUnit) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($acquisitionUnit);
            $entityManager->flush();
        }
    }

}