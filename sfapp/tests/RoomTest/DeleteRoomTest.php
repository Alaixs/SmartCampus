<?php

namespace App\Tests\RoomTest;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteRoomTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testEditValidData()
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

        $crawler = $client->request('GET', '/editRoom/' . $roomName);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Submit the form to delete the room
//        $form = $crawler->selectButton('Supprimer')->form();
//        $client->submit($form);

        //$entityManager = self::$container->get('doctrine')->getManager();

        // Query for the deleted room
        //$queryBuilder = $entityManager->createQueryBuilder();
        //$queryBuilder
        //    ->select('r')
        //    ->from('App\Entity\Room', 'r')
        //    ->where($queryBuilder->expr()->like('r.name', ':name'))
        //    ->setParameter('name', $roomName);
        //$query = $queryBuilder->getQuery();
        //$results = $query->getResult();

        // Check if the room is no longer in the database after deletion
        //$this->assertCount(0, $results, 'The room should not be present in the database after deletion');
    }
}

