<?php

namespace App\Tests;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RemoveRoomTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testDeleteRoom()
    {
        $roomName = 'D404';

        $client = static::createClient();

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

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $client->request('GET', '/detailRoom/' . $room->getId());

        $this->assertStringContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');

        $client->clickLink('Supprimer');
        $crawler = $client->followRedirect();
        $this->assertEquals($crawler->getUri(), 'http://localhost/admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertStringNotContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');
    }
}

