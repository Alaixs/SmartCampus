<?php

namespace App\Tests;

use App\Entity\AcquisitionUnit;
use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Domain\AcquisitionUnitState;

class SetAcquisitionUnitStatusToOperationalTest extends WebTestCase
{
    public function testSetAcquisitionUnitStatusToOperational()
    {
        $AUName = 'SA2000';

        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSA = new AcquisitionUnit();
        $newSA->setName($AUName);
        $newSA->setState(AcquisitionUnitState::ATTENTE_AFFECTATION->value);

        $entityManager->persist($newSA);
        $entityManager->flush();

        $this->assertInstanceOf(AcquisitionUnit::class, $newSA);

        $crawler = $client->request('GET', '/defAcquisitionUnitOperationnel/' . $newSA->getId());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
