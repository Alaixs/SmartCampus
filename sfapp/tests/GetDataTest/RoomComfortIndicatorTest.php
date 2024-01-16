<?php

namespace App\Tests;

use App\Domain\GetDataInteface;
use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Infrastructure\GetDataAPI;
use Monolog\Test\TestCase;

class RoomComfortIndicatorTest extends TestCase {

    public function testValidGetLastDataByType()
    {

        // Create Room and AU
        $au = new AcquisitionUnit();
        $au->setName('ESP-009');
        $room = new Room();
        $room->setAcquisitionUnit($au);

        $getDataMock = $this->createMock(GetDataAPI::class);

        $getDataMock->expects($this->once())
            ->method('getRoomComfortIndicator')
            ->willReturn(['temp' => 'OK', 'hum' => 'OK', 'co2' => 'OK']);

        $result = $getDataMock->getRoomComfortIndicator($room);
        dump($result);
        $this->assertEquals('OK', $result['temp']);
        $this->assertEquals('OK', $result['hum']);
        $this->assertEquals('OK', $result['co2']);


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