<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesAccessibleTest extends WebTestCase
{
    public function testIndexPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAdminPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/admin');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testAddRoomPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/addRoom');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testTechnicienPageIsAccessible()
    {
        $client = static::createClient();


        $client->request('GET', '/technicien');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddSAPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/addSA');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

//    public function testDeleteSAPageIsAccessible()
//    {
//        $client = static::createClient();
//
//        $client->request('GET', '/deleteSa');
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }

    public function testEditRoomPageIsAccessible()
    {
        $roomName = 'D400';

        $roomRepository = $this->getMockBuilder(RoomRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $room = new Room();
        $room->setName($roomName);
        $room->setCapacity(60);
        $room->setFloor(3);
        $room->setExposure('north');
        $room->setArea(60);
        $room->setNbWindows(5);
        $room->setHasComputers(true);

        $roomRepository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['name' => $roomName]))
            ->willReturn($room);

        $client = static::createClient();

        $client->getContainer()->set(RoomRepository::class, $roomRepository);

        $client->request('GET', '/editRoom/'. $roomName);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAssignSA()
    {
        $roomName = 'D450';

        $roomRepository = $this->getMockBuilder(RoomRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $room = new Room();
        $room->setName($roomName);
        $room->setCapacity(60);
        $room->setFloor(3);
        $room->setExposure('north');
        $room->setArea(60);
        $room->setNbWindows(5);
        $room->setHasComputers(true);

        $roomRepository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['name' => $roomName]))
            ->willReturn($room);

        $client = static::createClient();

        $client->getContainer()->set(RoomRepository::class, $roomRepository);

        $client->request('GET', '/assignSA/'. $roomName);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

}
