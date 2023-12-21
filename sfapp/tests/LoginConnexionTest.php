<?php

namespace App\Tests;

use App\Repository\AcquisitionUnitRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginConnexionTest extends WebTestCase
{

    public function testConnexion()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/login');   
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}