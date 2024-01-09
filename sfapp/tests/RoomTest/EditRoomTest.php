<?php

namespace App\Tests;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class EditRoomTest extends WebTestCase
{

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'yacine']);
        $this->client->loginUser($testUser);
    }

    public function testEditValidRoomName()
    {
        $roomName = 'D999';
        $newRoomName = 'D998';

        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'yacine'));
        $this->client->loginUser($testUser);
        $this->createRoom($roomName);
        $roomRepository = $this->client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $this->client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[name]' => $newRoomName));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();

        $this->assertStringContainsString($newRoomName, $this->client->getResponse()->getContent(), 'ca marche?');


        $this->deleteRoom($newRoomName);
    }

    public function testEditNotValidRoomName()
    {
        $roomName = 'D309';
        $newRoomName = 'D207';

        $this->createRoom($roomName);
        $roomRepository = $this->client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $this->client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[name]' => $newRoomName));

        $this->client->submit($form);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET','/detailRoom/' . $room->getId());
        $this->assertStringNotContainsString($newRoomName, $this->client->getResponse()->getContent(), 'ca marche?');


        $this->deleteRoom($roomName);
    }

    public function testEditValidFloor()
    {
        $roomName = 'D309';
        $newFloor = 1;

        $this->createRoom($roomName);
        $roomRepository = $this->client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $this->client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[floor]' => $newFloor));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertStringContainsString('1er', $this->client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($roomName);
    }

    public function testEditValidCapacity()
    {
        $roomName = 'D999';
        $newCapacity = 40;

        $this->createRoom($roomName);
        $roomRepository = $this->client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $this->client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[capacity]' => $newCapacity));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertStringContainsString('40 personnes', $this->client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($roomName);
    }

    public function testEditNotValidCapacity()
    {
        $roomName = 'D999';
        $newCapacity = -1;

        $this->createRoom($roomName);

        $roomRepository = $this->client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $this->client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[capacity]' => $newCapacity));

        $this->client->submit($form);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET','/detailRoom/' . $room->getId());
        $this->assertStringNotContainsString('1er', $this->client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($roomName);
    }

    public function testEditValidArea()
    {
        $roomName = 'D999';
        $newArea = 40;

        $this->createRoom($roomName);
        $roomRepository = $this->client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $this->client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[area]' => $newArea));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertStringContainsString('40 m²', $this->client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($roomName);
    }

    public function testEditNotValidArea()
    {
        $roomName = 'D999';
        $newArea = -1;

        $this->createRoom($roomName);

        $roomRepository = $this->client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $this->client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[area]' => $newArea));

        $this->client->submit($form);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET','/detailRoom/' . $room->getId());
        $this->assertStringNotContainsString('1er', $this->client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($roomName);
    }

    private function createRoom($roomName) : void
    {
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

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
    }

    private function deleteRoom($roomName): void
    {
        $roomRepository = $this->client->getContainer()->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(['name' => $roomName]);
    
        if ($room) {
            $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($room);
            $entityManager->flush();
        }
    }    

}