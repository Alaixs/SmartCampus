<?php

namespace App\Tests\RoomTest;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class AccessToRoomDataTest extends WebTestCase
{
    /**
     * La méthode testTechnicianHasAccessToRoomData() vérifie si une fois connecté,
     * le technicien peut accéder aux données des salles
     * @return void
     */
    public function testTechnicianHasAccessToRoomData()
    {
        $client = static::createClient();
        $this->login($client, 'technicien', 'jesuistechnicien');

        $newRoom = $this->createRoom($client, "D209");
        $client->request('GET', '/viewData/' . $newRoom->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $html = $client->getResponse()->getContent();
        $crawler = new Crawler($html);

        $this->assertCount(1, $crawler->filter('.dataVisualisation'));
        $this->deleteRoom($client, "D209");
    }

    /**
     * La méthode testMissionHeadHasAccessToRoomData() vérifie si une fois connecté,
     * le chargé de mission peut accéder aux données des salles
     * @return void
     */
    public function testMissionHeadHasAccessToRoomData()
    {
        $client = static::createClient();
        $this->login($client, 'referent', 'jesuisreferent');

        $newRoom = $this->createRoom($client, "D209");
        $client->request('GET', '/viewData/' . $newRoom->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $html = $client->getResponse()->getContent();
        $crawler = new Crawler($html);

        $this->assertCount(1, $crawler->filter('.dataVisualisation'));
        $this->deleteRoom($client, "D209");
    }

    /**
     * La méthode testNotLoggedUserHasAccessToRoomData() vérifie si un
     * utilisateur non connecté peut accéder aux données des salles
     * @return void
     */
    public function testNotLoggedUserHasAccessToRoomData()
    {
        $client = static::createClient();

        $newRoom = $this->createRoom($client, "D209");
        $client->request('GET', '/viewData/' . $newRoom->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $html = $client->getResponse()->getContent();
        $crawler = new Crawler($html);

        $this->assertCount(1, $crawler->filter('.dataVisualisation'));
        $this->deleteRoom($client, "D209");
    }

    /**
     * @brief creates a room
     * @param $client
     * @param $roomName
     * @return Room
     */
    private function createRoom($client, $roomName): Room
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

        return $newRoom;
    }

    /**
     * @brief deletes a given room
     * @param $client
     * @param $roomName
     * @return void
     */
    private function deleteRoom($client, $roomName): void
    {
        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(['name' => $roomName]);

        if ($room) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($room);
            $entityManager->flush();
        }
    }

    /**
     * La méthode login() connecte un client avec son identifiant et son mot de passe
     * @param $client
     * @param string $username
     * @param string $password
     */
    private function login($client, string $username, string $password): void
    {
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $username,
            '_password' => $password,
        ]);
        $client->submit($form);
        $client->followRedirect();
    }
}