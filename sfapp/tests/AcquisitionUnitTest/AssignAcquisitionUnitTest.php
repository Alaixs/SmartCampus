<?php

namespace App\Tests\TechnicienTest\RoomTest\RoomTest\RoomTest\LoginTest\GetDataTest\AcquisitionUnitTest\AcquisitionUnitTest\AcquisitionUnitTest\AcquisitionUnitTest;

use App\Domain\AcquisitionUnitOperatingState;
use App\Entity\Room;
use App\Repository\AcquisitionUnitRepository;
use App\Entity\AcquisitionUnit;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class AssignAcquisitionUnitTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le formulaire est valide avec des données correctes.
     * @return void
     */
    public function testSubmitValidData()
    {
        $auNumber = 'SA9999';
        $roomName = 'D999';

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(array('username' => 'technicien'));
        $client->loginUser($testUser);

        $room = $this->createRoom($client, $roomName);
        $au = $this->createSa($client, $auNumber);

        $crawler = $client->request('GET', '/assignAcquisitionUnit/' . $room->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Affecter')->form();
        //     I complete the form
        $form->setValues(array('assign_acquisition_unit_form[acquisitionUnit]' => $au->getId()));

        $client->submit($form);
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString($auNumber, $client->getResponse()->getContent(), 'ca marche?');

        $this->deleteRoom($client, $roomName);
        $this->deleteSa($client, $auNumber);
    }



    private function createSa($client, $auNumber) : AcquisitionUnit
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSa = new AcquisitionUnit();
        $newSa->setName($auNumber);
        $newSa->setState(AcquisitionUnitOperatingState::WAITING_FOR_ASSIGNMENT->value);

        $entityManager->persist($newSa);
        $entityManager->flush();

        return $newSa;
    }

    private function createRoom($client, $roomName) : Room
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
    private function deleteSa($client, $auNumber) : void
    {
        $acquisitionUnitRepository = $client->getContainer()->get(AcquisitionUnitRepository::class);
        $sa = $acquisitionUnitRepository->findOneBy(array('name' => $auNumber));
        if ($sa) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($sa);
            $entityManager->flush();
        }
    }

}
