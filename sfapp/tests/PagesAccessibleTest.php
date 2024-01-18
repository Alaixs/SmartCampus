<?php

namespace App\Tests;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class PagesAccessibleTest extends WebTestCase
{
    /**
     * La méthode testIndexPageIsAccessible() verifie si le code de retour de la page index est bien 200
     * @return void
     */

    private string $roomName = '404';

    private function getClientWithLoggedInUser(string $username): KernelBrowser
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => $username]);
        $client->loginUser($testUser);

        return $client;
    }

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
        $client = $this->getClientWithLoggedInUser('référent');

        $client->request('GET', '/admin');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testAddRoomPageIsAccessible() verifie si le code de retour de la page addRoom est bien 200
     * @return void
     */
    public function testAddRoomPageIsAccessible()
    {
        $client = $this->getClientWithLoggedInUser('référent');

        $client->request('GET', '/addRoom');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testTechnicienPageIsAccessible() verifie si le code de retour de la page technicien est bien 200
     * @return void
     */
    public function testTechnicienPageIsAccessible()
    {
        $client = $this->getClientWithLoggedInUser('technicien');

        $client->request('GET', '/admin');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testAddSAPageIsAccessible() verifie si le code de retour de la page addSA est bien 200
     * @return void
     */
    public function testAddAcquisitionUnitPageIsAccessible()
    {
        $client = $this->getClientWithLoggedInUser('technicien');

        $client->request('GET', '/addAcquisitionUnit');

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

        $client = $this->getClientWithLoggedInUser('référent');

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
    public function testAssignAcquisitionUnitIsAccessible()
    {

        $client = $this->getClientWithLoggedInUser('technicien');
        
        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $this->roomName));

        $client->request('GET', '/assignAcquisitionUnit/' . $room->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    /**
     * La méthode testUnAssignSAIsAccessible() verifie si le code de retour de la page testUnAssignSAIsAccessible est bien 302
     * @return void
     */
    public function testUnAssignSAIsAccessible()
    {

        $client = $this->getClientWithLoggedInUser('technicien');

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $this->roomName));

        $client->request('GET', '/unassignAcquisitionUnit/'. $room->getId());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

    }

    /**
     * La méthode testRemoveRoomIsAccessible() verifie si le code de retour de la page testRemoveRoomIsAccessible est bien 302
     * @return void
     */
    public function testRemoveRoomIsAccessible()
    {
        $client = $this->getClientWithLoggedInUser('référent');

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $this->roomName));

        $client->request('GET', '/removeRoom/'. $room->getId());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testLoginIsAccessible() verifie si le code de retour de la page testLoginIsAccessible est bien 200
     * @return void
     */
    public function testLoginIsAccessible()
    {
        $client = $this->getClientWithLoggedInUser('référent');

        $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * La méthode testViewDataPageIsAccessible() verifie si le code de retour de la page testViewDataPageIsAccessible est bien 200
     * @return void
     */
    public function testViewDataPageIsAccessible()
    {

        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newRoom = new Room();
        $newRoom->setName('D981');
        $newRoom->setArea(10);
        $newRoom->setFloor(2);
        $newRoom->setExposure('Nord');
        $newRoom->setCapacity(20);
        $newRoom->setHasComputers(0);
        $newRoom->setNbWindows(4);

        $entityManager->persist($newRoom);
        $entityManager->flush();

        $client->request('GET', '/viewData/'. $newRoom->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
