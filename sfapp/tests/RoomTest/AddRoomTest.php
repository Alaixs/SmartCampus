<?php

namespace App\Tests;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddRoomTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testAddRoomValidData()
    {
        $roomName = 'D309';
        $client = static::createClient();

        $crawler = $client->request('GET', '/addRoom' );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        // I complete the form
        $form->setValues(array(
            'add_room_form[name]' => $roomName,
            'add_room_form[floor]' => 2,
            'add_room_form[capacity]' => 40,
            'add_room_form[area]' => 50,
            'add_room_form[exposure]' => 'Nord',
            'add_room_form[nbWindows]' => 40,
        ));

        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertStringContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
    }

    public function testSubmitRoomAlreadyExist()
    {
        $roomName = 'D207';
        $client = static::createClient();

        $crawler = $client->request('GET', '/addRoom' );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        // I complete the form
        $form->setValues(array(
            'add_room_form[name]' => $roomName,
            'add_room_form[floor]' => 2,
            'add_room_form[capacity]' => 40,
            'add_room_form[area]' => 50,
            'add_room_form[exposure]' => 'Nord',
            'add_room_form[nbWindows]' => 40,
        ));
        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    public function testSubmitNegativeCapacity()
    {
        $roomName = 'D309';
        $client = static::createClient();

        $crawler = $client->request('GET', '/addRoom' );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        // I complete the form
        $form->setValues(array(
            'add_room_form[name]' => $roomName,
            'add_room_form[floor]' => 2,
            'add_room_form[capacity]' => -40,
            'add_room_form[area]' => 50,
            'add_room_form[exposure]' => 'Nord',
            'add_room_form[nbWindows]' => 40,
        ));
        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/addRoom', $client->getCrawler()->getUri());

        $client->request('GET', '/admin' );
        $this->assertStringNotContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');
    }

    public function testSubmitNegativeAreaSize()
    {
        $roomName = 'D309';
        $client = static::createClient();

        $crawler = $client->request('GET', '/addRoom' );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        // I complete the form
        $form->setValues(array(
            'add_room_form[name]' => $roomName,
            'add_room_form[floor]' => 2,
            'add_room_form[capacity]' => 40,
            'add_room_form[area]' => -50,
            'add_room_form[exposure]' => 'Nord',
            'add_room_form[nbWindows]' => 40,
        ));

        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/addRoom', $client->getCrawler()->getUri());

        $client->request('GET', '/admin' );
        $this->assertStringNotContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');
    }

    public function testSubmitNullAreaSize()
    {
        $roomName = 'D309';
        $client = static::createClient();

        $crawler = $client->request('GET', '/addRoom' );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        // I complete the form
        $form->setValues(array(
            'add_room_form[name]' => $roomName,
            'add_room_form[floor]' => 2,
            'add_room_form[capacity]' => 40,
            'add_room_form[area]' => 0,
            'add_room_form[exposure]' => 'Nord',
            'add_room_form[nbWindows]' => 40,
        ));

        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/addRoom', $client->getCrawler()->getUri());

        $client->request('GET', '/admin' );
        $this->assertStringNotContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');
    }

    public function testSubmitNegativeNumberOfWindows()
    {
        $roomName = 'D309';
        $client = static::createClient();

        $crawler = $client->request('GET', '/addRoom' );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        // I complete the form
        $form->setValues(array(
            'add_room_form[name]' => $roomName,
            'add_room_form[floor]' => 2,
            'add_room_form[capacity]' => 40,
            'add_room_form[area]' => 50,
            'add_room_form[exposure]' => 'Nord',
            'add_room_form[nbWindows]' => -4,
        ));

        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/addRoom', $client->getCrawler()->getUri());

        $client->request('GET', '/admin' );
        $this->assertStringNotContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');
    }

    private function deleteRoom($client, $roomName) : void
    {
        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(array('name' => $roomName));
        if ($room)
        {
                $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
                $entityManager->remove($room);
                $entityManager->flush();
        }
    }
}
