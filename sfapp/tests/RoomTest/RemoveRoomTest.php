<?php

namespace App\Tests\RoomTest;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class RemoveRoomTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testRemoveRoom()
    {
        $roomName = 'D404';

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'référent'));
        $client->loginUser($testUser);

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

        $crawler = $client->request('GET', '/roomDetail/' . $room->getId());

        $this->assertStringContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');

        $link = $crawler->selectLink('Confirmer')->first()->link();
        $client->click($link);

        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringNotContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        if($room)
        {
            $entityManager->remove($room);
            $entityManager->flush();
        }

    }
}

