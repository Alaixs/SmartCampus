<?php

namespace App\Tests;

use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class SetSaOperationalTest extends WebTestCase
{
    public function testValidData()
    {

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'yacine'));
        $client->loginUser($testUser);

        $roomName = 'D999';
        $saNumber = 'SA8759';

        $this->addRoomAndSa($client, $roomName, $saNumber);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(array('name' => $roomName));
        $client->request('GET', '/manageAcquisitionUnit/' . $room->getAcquisitionUnit()->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->clickLink('Rendre le SA opérationnel');
        $client->followRedirect();
        $this->assertStringContainsString('Opérationnel', $client->getResponse()->getContent());
        $this->deleteRoom($client, $room);
        $this->deleteSA($client, $room->getAcquisitionUnit());
    }

    public function testNotValidData()
    {

        $roomName = 'D987';
        $saNumber = 'SA8759';
        $client = static::createClient();

        $this->addRoomAndSa($client, $roomName, $saNumber);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(array('name' => $roomName));


        $client->request('GET', '/manageAcquisitionUnit/' . $room->getAcquisitionUnit()->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->clickLink('Rendre le SA opérationnel');
        $client->followRedirect();
        $this->assertStringNotContainsString("ok.png", $client->getResponse()->getContent());
        $this->deleteRoom($client, $room);
        $this->deleteSA($client, $room->getAcquisitionUnit());
    }

    private function addRoomAndSa($client, $roomName, $saNumber) : void
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSa = new AcquisitionUnit();
        $newSa->setName($saNumber);
        $newSa->setState(AcquisitionUnitState::ATTENTE_INSTALLATION->value);

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

    private function deleteRoom($client, $room) : void
    {
        if ($room)
        {
            $client->request('GET', '/removeRoom/' . $room->getId());
        }
    }

    private function deleteSA($client, $sa) : void
    {
        if($sa)
        {
            $client->request('GET', '/removeSA/' . $sa->getId());
        }
    }
}