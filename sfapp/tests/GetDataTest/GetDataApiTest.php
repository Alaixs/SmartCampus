<?php

namespace App\Tests\TechnicienTest\RoomTest\RoomTest\RoomTest\LoginTest\GetDataTest;

use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Infrastructure\GetDataAPI;
use PHPUnit\Framework\TestCase;

class GetDataApiTest extends TestCase {

    public function testValidGetLastDataByType()
    {

        $getData = new GetDataAPI();

        // Create Room and AU
        $au = new AcquisitionUnit();
        $au->setName('ESP-009');
        $room = new Room();
        $room->setAcquisitionUnit($au);


        $value = $getData->getLastValueByType($room, 'hum');

        $this->assertTrue($value[0] != -1, 'WOw');
    }

    public function testInvalidGetLastDataByType()
    {
        $getData = new GetDataAPI();

        // Create Room and AU
        $au = new AcquisitionUnit();
        $au->setName('ESP-845');
        $room = new Room();
        $room->setAcquisitionUnit($au);


        $value = $getData->getLastValueByType($room, 'hum');

        $this->assertTrue($value[0] == -1 && $value[1] == 0, 'WOw');
    }
}