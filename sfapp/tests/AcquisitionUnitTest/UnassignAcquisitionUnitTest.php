<?php

namespace App\Tests;

use App\Domain\StateSA;
use App\Entity\Room;
use App\Repository\AcquisitionUnitRepository;
use App\Entity\AcquisitionUnit;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class UnassignAcquisitionUnitTest extends WebTestCase
{
    public function testUnassignSa()
    {
        $roomName = 'D444';
        $saNumber = 'SA4321';

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'yacine'));
        $client->loginUser($testUser);        

        $this->addRoomAndSa($client, $roomName, $saNumber);

        $roomRepository = $client->getContainer()->get(RoomRepository::class);
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);

        $room = $roomRepository->findOneBy(array('name' => $roomName));

        $crawler = $client->request('GET', '/detailRoom/' . $room->getId());

        $this->assertStringContainsString($roomName, $client->getResponse()->getContent(), 'ca marche?');

        $link = $crawler->selectLink('Confirmer')->eq(1)->link();
        $client->click($link);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringNotContainsString($saNumber, $client->getResponse()->getContent(), 'ca marche?');

        $room = $roomRepository->findOneBy(array('name' => $roomName));
        $sa = $acquisitionUnitRepository->findOneBy(array('number' => $saNumber));
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $client->request('GET','/removeRoom/' . $room->getId());

        $client->request('GET','/removeSA/' . $sa->getId());
    }

    private function addRoomAndSa($client, $roomName, $saNumber) : void
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSa = new AcquisitionUnit();
        $newSa->setNumber($saNumber);
        $newSa->setState(StateSA::ATTENTE_INSTALLATION->value);

        $newRoom = new Room();
        $newRoom->setName($roomName);
        $newRoom->setArea(10);
        $newRoom->setFloor(2);
        $newRoom->setExposure('Nord');
        $newRoom->setCapacity(20);
        $newRoom->setHasComputers(0);
        $newRoom->setNbWindows(4);
        $newRoom->setSA($newSa);

        $entityManager->persist($newSa);
        $entityManager->persist($newRoom);
        $entityManager->flush();


    }

}