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
        $SANumber = 'SA2000';

        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $newSA = new AcquisitionUnit();
        $newSA->setNumber($SANumber);
        $newSA->setState(AcquisitionUnitState::ATTENTE_AFFECTATION->value);

        $entityManager->persist($newSA);
        $entityManager->flush();

        $this->assertInstanceOf(AcquisitionUnit::class, $newSA);

        $crawler = $client->request('GET', '/defSAoperationnel/' . $SANumber);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

