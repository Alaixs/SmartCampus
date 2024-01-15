<?php

namespace App\Tests\TechnicienTest\RoomTest\RoomTest\RoomTest\LoginTest\GetDataTest\AcquisitionUnitTest;

use App\Domain\AcquisitionUnitOperatingState;
use App\Entity\Room;
use App\Repository\AcquisitionUnitRepository;
use App\Entity\AcquisitionUnit;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class UnassignAcquisitionUnitTest extends WebTestCase
{
    public function testUnassignSa()
    {
        $roomName = 'D444';
        $saNumber = 'SA4321';

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'référent'));
        $client->loginUser($testUser);        

        $this->addRoomAndSa($client, $roomName, $saNumber);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $crawler = $client->request('GET', '/roomDetail/' . $room->getId());

        $link = $crawler->selectLink('Confirmer')->eq(1)->link();
        $client->click($link);

        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringNotContainsString($saNumber, $client->getResponse()->getContent(), 'ca marche?');

        $room = $roomRepository->findOneBy(array('name' => $roomName));
        $sa = $acquisitionUnitRepository->findOneBy(array('name' => $saNumber));
        $client->getContainer()->get('doctrine.orm.entity_manager');

        $client->request('GET','/removeRoom/' . $room->getId());

        $client->request('GET','/removeAcquisitionUnit/' . $sa->getId());
    }

    private function addRoomAndSa($client, $roomName, $saNumber) : void
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSa = new AcquisitionUnit();
        $newSa->setName($saNumber);
        $newSa->setState(AcquisitionUnitOperatingState::WAITING_FOR_INSTALLATION->value);

        $newRoom = new Room();
        $newRoom->setName($roomName);
        $newRoom->setArea(10);
        $newRoom->setFloor(2);
        $newRoom->setExposure('Nord');
        $newRoom->setCapacity(20);
        $newRoom->setHasComputers(0);
        $newRoom->setNbWindows(4);
        $newRoom->setAcquisitionUnit($newSa);

        $entityManager->persist($newSa);
        $entityManager->persist($newRoom);
        $entityManager->flush();


    }

}