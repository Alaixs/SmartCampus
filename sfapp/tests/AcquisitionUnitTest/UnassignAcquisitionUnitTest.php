<?php

namespace App\Tests;

use App\Domain\AcquisitionUnitState;
use App\Entity\Room;
use App\Repository\AcquisitionUnitRepository;
use App\Entity\AcquisitionUnit;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class UnassignAcquisitionUnitTest extends WebTestCase
{
    public function testUnassignAcquisitionUnit()
    {
        $roomName = 'D309';
        $acquisitionUnitName = 'ESP-017';

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'yacine'));
        $client->loginUser($testUser);        

        $this->addRoomAndAcquisitionUnit($client, $roomName, $acquisitionUnitName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $crawler = $client->request('GET', '/roomDetail/' . $room->getId());

        $this->assertStringContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');

        $link = $crawler->selectLink('Confirmer')->eq(1)->link();
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringNotContainsString($acquisitionUnitName, $client->getResponse()->getContent(), 'ca marche?');

        $room = $roomRepository->findOneBy(array('name' => $roomName));
        $acquisitionUnit = $acquisitionUnitRepository->findOneBy(array('number' => $acquisitionUnitName));

        $client->request('GET','/removeRoom/' . $room->getId());

        $client->request('GET','/removeAU/' . $acquisitionUnit->getId());
    }

    private function addRoomAndAcquisitionUnit($client, $roomName, $acquisitionUnitName) : void
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newAcquisitionUnit = new AcquisitionUnit();
        $newAcquisitionUnit->setName($acquisitionUnitName);
        $newAcquisitionUnit->setState(AcquisitionUnitState::ATTENTE_INSTALLATION->value);

        $newRoom = new Room();
        $newRoom->setName($roomName);
        $newRoom->setArea(10);
        $newRoom->setFloor(2);
        $newRoom->setExposure('Nord');
        $newRoom->setCapacity(20);
        $newRoom->setHasComputers(0);
        $newRoom->setNbWindows(4);
        $newRoom->setAcquisitionUnit($newAcquisitionUnit);

        $entityManager->persist($newAcquisitionUnit);
        $entityManager->persist($newRoom);
        $entityManager->flush();
    }
}