<?php

namespace App\Tests;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditRoomTest extends WebTestCase
{
    public function testEditValidRoomName()
    {
        $roomName = 'D999';
        $newRoomName = 'D998';
        $client = static::createClient();

        $this->createRoom($client, $roomName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[name]' => $newRoomName));

        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();

        $this->assertStringContainsString($newRoomName, $client->getResponse()->getContent(), 'ca marche?');


        $this->deleteRoom($client, $newRoomName);
    }

    public function testEditNotValidRoomName()
    {
        $roomName = 'D999';
        $newRoomName = 'D207';
        $client = static::createClient();

        $this->createRoom($client, $roomName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[name]' => $newRoomName));

        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $client->request('GET','/detailRoom/' . $room->getId());
        $this->assertStringNotContainsString($newRoomName, $client->getResponse()->getContent(), 'ca marche?');


        $this->deleteRoom($client, $roomName);
    }

    public function testEditValidFloor()
    {
        $roomName = 'D999';
        $newFloor = 1;
        $client = static::createClient();

        $this->createRoom($client, $roomName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[floor]' => $newFloor));

        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertStringContainsString('1er', $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
    }

    public function testEditValidCapacity()
    {
        $roomName = 'D999';
        $newCapacity = 40;
        $client = static::createClient();

        $this->createRoom($client, $roomName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[capacity]' => $newCapacity));

        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertStringContainsString('40 personnes', $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
    }

    public function testEditNotValidCapacity()
    {
        $roomName = 'D999';
        $newCapacity = -1;
        $client = static::createClient();

        $this->createRoom($client, $roomName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[capacity]' => $newCapacity));

        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $client->request('GET','/detailRoom/' . $room->getId());
        $this->assertStringNotContainsString('1er', $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
    }

    public function testEditValidArea()
    {
        $roomName = 'D999';
        $newArea = 40;
        $client = static::createClient();

        $this->createRoom($client, $roomName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[area]' => $newArea));

        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertStringContainsString('40 m²', $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
    }

    public function testEditNotValidArea()
    {
        $roomName = 'D999';
        $newArea = -1;
        $client = static::createClient();

        $this->createRoom($client, $roomName);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $client->request('GET', '/detailRoom/' . $room->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->clickLink('Modifier la salle');

        $form = $crawler->selectButton('Modifier')->form();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form->setValues(array('add_room_form[area]' => $newArea));

        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $client->request('GET','/detailRoom/' . $room->getId());
        $this->assertStringNotContainsString('1er', $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
    }

    private function createRoom($client, $roomName) : void
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
    }

    private function deleteRoom($client, $roomName) : void
    {
        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(array('name' => $roomName));
        if ($room) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($room);
            $entityManager->flush();
        }
    }

}