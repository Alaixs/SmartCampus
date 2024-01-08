<?php

namespace App\Tests;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Entity\Room;

class AddRoomTest extends WebTestCase
{

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'yacine']);
        $this->client->loginUser($testUser);
    }

    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testAddRoomValidData()
    {
        $roomName = 'D001';

        $crawler = $this->client->request('GET', '/addRoom');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

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

        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $room = $this->searchRoom($roomName);

        $this->assertNotNull($room, 'La salle devrait être présente dans la base de données');

        $this->deleteRoom($roomName);
    }


    public function testSubmitRoomAlreadyExist()
    {
        $roomName = 'D207';

        $crawler = $this->client->request('GET', '/addRoom');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

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
        $this->client->submit($form);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
    }


    public function testSubmitNegativeCapacity()
    {
        $roomName = 'D003';
    
        $crawler = $this->client->request('GET', '/addRoom');
    
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    
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
        $this->client->submit($form);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/addRoom', $this->client->getCrawler()->getUri());
    
        $room = $this->searchRoom($roomName);

        $this->assertNull($room, 'La salle ne devrait pas être présente dans la base de données');
    }
    

    public function testSubmitNegativeAeraSize()
    {
        $roomName = 'D004';

        $crawler = $this->client->request('GET', '/addRoom');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

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

        $this->client->submit($form);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/addRoom', $this->client->getCrawler()->getUri());
        
        $room = $this->searchRoom($roomName);

        $this->assertNull($room, 'La salle ne devrait pas être présente dans la base de données');

    }

    public function testSubmitNullAeraSize()
    {
        $roomName = 'D005';

        $crawler = $this->client->request('GET', '/addRoom');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

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

        $this->client->submit($form);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/addRoom', $this->client->getCrawler()->getUri());

        $room = $this->searchRoom($roomName);

        $this->assertNull($room, 'La salle ne devrait pas être présente dans la base de données');

    }

    public function testSubmitNegativeNumberOfWindows()
    {
        $roomName = 'D006';

        $crawler = $this->client->request('GET', '/addRoom');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

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

        $this->client->submit($form);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/addRoom', $this->client->getCrawler()->getUri());
        
        $room = $this->searchRoom($roomName);

        $this->assertNull($room, 'La salle ne devrait pas être présente dans la base de données');

    }

    private function searchRoom($roomName): ?Room
    {
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $roomRepository = $entityManager->getRepository(Room::class);
        $room = $roomRepository->findOneBy(['name' => $roomName]);
        return $room;
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
