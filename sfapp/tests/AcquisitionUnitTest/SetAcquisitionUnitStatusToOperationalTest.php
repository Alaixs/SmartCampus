<?php

namespace App\Tests\AcquisitionUnitTest;

use App\Entity\AcquisitionUnit;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Domain\AcquisitionUnitOperatingState;

class SetAcquisitionUnitStatusToOperationalTest extends WebTestCase
{
    public function testSetAcquisitionUnitStatusToOperational()
    {
        $AUName = 'SA2000';

        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSA = new AcquisitionUnit();
        $newSA->setName($AUName);
        $newSA->setState(AcquisitionUnitOperatingState::WAITING_FOR_ASSIGNMENT->value);

        $entityManager->persist($newSA);
        $entityManager->flush();

        $this->assertInstanceOf(AcquisitionUnit::class, $newSA);

        $client->request('GET', '/setAcquisitionUnitOperational/' . $newSA->getId());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}

