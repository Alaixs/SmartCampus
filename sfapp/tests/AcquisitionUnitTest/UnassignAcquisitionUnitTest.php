<?php

namespace App\Tests\AcquisitionUnitTest;

use App\Domain\AcquisitionUnitOperatingState;
use App\Entity\Room;
use App\Entity\AcquisitionUnit;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class UnassignAcquisitionUnitTest extends WebTestCase
{
    public function testUnassignSa()
    {
        $roomName = 'D444';
        $auNumber = 'SA4321';

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'technicien'));
        $client->loginUser($testUser);        

        $room = $this->addRoomAndSa($client, $roomName, $auNumber);

        $au = $room->getAcquisitionUnit();

        $crawler = $client->request('GET', '/manageAcquisitionUnit/' . $au->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Désaffecter le SA')->link();
        $client->click($link);

        $client->followRedirect();
        $this->assertStringNotContainsString($auNumber, $client->getResponse()->getContent(), 'ca marche?');

        $client->request('GET','/removeRoom/' . $room->getId());

        $client->request('GET','/removeAcquisitionUnit/' . $au->getId());
    }

    private function addRoomAndSa($client, $roomName, $saNumber) : Room
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSa = new AcquisitionUnit();
        $newSa->setName($saNumber);
        $newSa->setState(AcquisitionUnitOperatingState::WAITING_FOR_INSTALLATION->value);

        $newRoom = new Room();
        $newRoom->setName($roomName);
        $newRoom->setArea(10);
        $newRoom->setFloor(2);
        $newRoom->setExposure('Nord');
        $newRoom->setCapacity(20);
        $newRoom->setHasComputers(0);
        $newRoom->setNbWindows(4);
        $newRoom->setAcquisitionUnit($newSa);

        $entityManager->persist($newSa);
        $entityManager->persist($newRoom);
        $entityManager->flush();

        return $newRoom;
    }

}