<?php

namespace App\Tests;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesAccessibleTest extends WebTestCase
{
    /**
     * La méthode testIndexPageIsAccessible() verifie si le code de retour de la page index est bien 200
     * @return void
     */

    private string $roomName = '404';

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
//    public function testRemoveSAPageIsAccessible()
//    {
//        $client = static::createClient();
//
//        $client->request('GET', '/removeSA');
//
//        $this->assertEquals(500, $client->getResponse()->getStatusCode());
//    }

    /**
     * La méthode testEditRoomPageIsAccessible() verifie si le code de retour de la page editRoom est bien 200
     * @return void
     */
    public function testEditRoomPageIsAccessible()
    {


        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newRoom = new Room();
        $newRoom->setName('404');
        $newRoom->setArea(10);
        $newRoom->setFloor(2);
        $newRoom->setExposure('Nord');
        $newRoom->setCapacity(20);
        $newRoom->setHasComputers(0);
        $newRoom->setNbWindows(4);

        $entityManager->persist($newRoom);
        $entityManager->flush();

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $this->roomName));

        $client->request('GET', '/editRoom/'. $room->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    /**
     * La méthode testAssignSA() verifie si le code de retour de la page assignSA est bien 200
     * @return void
     */
    public function testAssignSAIsAccessible()
    {

        $client = static::createClient();

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $this->roomName));

        $client->request('GET', '/assignSA/' . $room->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testUnAssignSAIsAccessible()
    {

        $client = static::createClient();

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $this->roomName));

        $client->request('GET', '/unAssignSA/'. $room->getId());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

    }

    public function testRemoveRoomIsAccessible()
    {
        $client = static::createClient();

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $this->roomName));

        $client->request('GET', '/removeRoom/'. $room->getId());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testLoginIsAccessible()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
