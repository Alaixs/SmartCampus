<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesAccessibleTest extends WebTestCase
{
    /**
     * La méthode testIndexPageIsAccessible() verifie si le code de retour de la page index est bien 200
     * @return void
     */
    public function testIndexPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testAdminPageIsAccessible() verifie si le code de retour de la page admin est bien 200
     * @return void
     */
    public function testAdminPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/admin');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testAddRoomPageIsAccessible() verifie si le code de retour de la page addRoom est bien 200
     * @return void
     */
    public function testAddRoomPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/addRoom');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testTechnicienPageIsAccessible() verifie si le code de retour de la page technicien est bien 200
     * @return void
     */
    public function testTechnicienPageIsAccessible()
    {
        $client = static::createClient();


        $client->request('GET', '/technicien');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testAddSAPageIsAccessible() verifie si le code de retour de la page addSA est bien 200
     * @return void
     */
    public function testAddSAPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/addSA');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testDeleteSAPageIsAccessible() verifie si le code de retour de la page deleteSa est bien 200
     * @return void
     */
    public function testDeleteSAPageIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/deleteSa');
        //A modifier quand fonctionnel
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testEditRoomPageIsAccessible() verifie si le code de retour de la page editRoom est bien 200
     * @return void
     */
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


    /**
     * La méthode testAssignSA() verifie si le code de retour de la page assignSA est bien 200
     * @return void
     */
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
