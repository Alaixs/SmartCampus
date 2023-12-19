<?php

namespace App\Tests;

use App\Entity\AcquisitionUnit;
use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SetAcquisitionUnitStatusToOperationalTest extends WebTestCase
{
    public function testSetAcquisitionUnitStatusToOperational()
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $saNumber = 'SA1000';
        $SA1000 = $entityManager->getRepository(AcquisitionUnit::class)->findOneBy(['number' => $saNumber]);

        $this->assertInstanceOf(AcquisitionUnit::class, $SA1000);

        $crawler = $client->request('GET', '/defSAoperationnel/{idSA}' . $saNumber);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

