<?php

namespace App\Tests\Infrastructure;

use App\Domain\AcquisitionUnitOperatingState;
use App\Domain\DataManagerInterface;
use App\Domain\GetDataInterface;
use App\Entity\AcquisitionUnit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\Cache\CacheInterface;

class DataManagerTest extends KernelTestCase
{
    protected function setUp(): void
    {
        // Delete cache
        exec('php bin/console cache:pool:clear cache.app ');
    }

    public static function tearDownAfterClass(): void
    {
        // Delete cache
        exec('php bin/console cache:pool:clear cache.app ');
    }

    public function testGetWithAcquisitionUnitIsNull()
    {
        self::bootKernel();

        $container = static::getContainer();

        $validData = array('temp' => array(-1, 0), 'hum' => array(-1, 0), 'co2' => array(-1, 0));

        $newAcquisitionUnit = null;

        $dataManager = $container->get(DataManagerInterface::class);

        $data = $dataManager->get($newAcquisitionUnit);

        $this->assertEquals($validData, $data);
    }

    public function testGetWithCache()
    {
        self::bootKernel();

        $container = static::getContainer();

        // Save data in cache
        $AcquisitionUnit = new AcquisitionUnit();
        $AcquisitionUnit->setName('ESP-TEST');

        $cache = $container->get(CacheInterface::class);

        $cacheItem = $cache->getItem($AcquisitionUnit->getName());
        $data = array('temp' => [90, 45]);

        $cacheItem->set($data);
        $cache->save($cacheItem);

        // Check if get use cache and return good data
        $dataManager = $container->get(DataManagerInterface::class);

        $dataInCache = $dataManager->get($AcquisitionUnit);

        $this->assertEquals($dataInCache, $data);
    }

    public function testGetWithoutCache()
    {
        self::bootKernel();

        $container = static::getContainer();

        $validData = array('temp' => array(45, '2023:01:15 10:15:15'), 'hum' => array(12, '2023:01:15 10:15:15'), 'co2' => array(40, '2023:01:15 10:15:15'));

        $this->createGetDataMockWithLastValueByType($container, array(
            array(45, '2023:01:15 10:15:15'),
            array(12, '2023:01:15 10:15:15'),
            array(40, '2023:01:15 10:15:15')
        ));

        $acquisitionUnit = new AcquisitionUnit();
        $acquisitionUnit->setName('ESP-TEST');

        $dataManager = $container->get(DataManagerInterface::class);

        $data = $dataManager->get($acquisitionUnit);
        $this->assertEquals($data, $validData);
    }

    public function testCheckIfSetDataInCache()
    {
        self::bootKernel();

        $container = static::getContainer();

        // Use method get to stock data in cache
        $acquisitionUnit = new AcquisitionUnit();
        $acquisitionUnit->setName('ESP-TEST');

        $this->createGetDataMockWithLastValueByType($container, array(
            array(75, '2023:01:15 10:15:15'),
            array(15, '2023:01:25 10:15:15'),
            array(20, '2023:01:11 10:15:15')));

        $dataManager = $container->get(DataManagerInterface::class);
        $dataManager->get($acquisitionUnit);

        // Check if data is save in cache
        $cache = $container->get(CacheInterface::class);
        $cacheItem = $cache->getItem($acquisitionUnit->getName());
        $this->assertTrue($cacheItem->isHit());
        $validData = array('temp' => array(75, '2023:01:15 10:15:15'), 'hum' => array(15, '2023:01:25 10:15:15'),
            'co2' => array(20, '2023:01:11 10:15:15'));

        $this->assertEquals($cacheItem->get(), $validData);
    }

    public function testSetChangeAcquisitionUnitStateOnOutlierData()
    {
        self::bootKernel();

        $container = static::getContainer();

        $currentDate = date('Y-m-d H:i:s', time() + 3500);
        $this->createGetDataMockWithLastValueByType($container, array(
                array(150,$currentDate),
                array(15, $currentDate),
                array(20, $currentDate)));

        $acquisitionUnit = new AcquisitionUnit();
        $acquisitionUnit->setState(AcquisitionUnitOperatingState::OPERATIONAL->value);
        $acquisitionUnit->setName('ESP-TEST');

        $dataManager = $container->get(DataManagerInterface::class);
        $dataManager->get($acquisitionUnit);

        $this->assertEquals($acquisitionUnit->getState(), AcquisitionUnitOperatingState::FAILURE->value);
    }

    public function testSetChangesAcquisitionUnitStateOnOutdatedData()
    {
        self::bootKernel();

        $container = static::getContainer();

        $InvalidDate = date('Y-m-d H:i:s', time() - 960);
        $this->createGetDataMockWithLastValueByType($container, array(
            array(22, $InvalidDate),
            array(15, $InvalidDate),
            array(20, $InvalidDate)));

        $acquisitionUnit = new AcquisitionUnit();
        $acquisitionUnit->setState(AcquisitionUnitOperatingState::OPERATIONAL->value);
        $acquisitionUnit->setName('ESP-TEST');

        $dataManager = $container->get(DataManagerInterface::class);
        $dataManager->get($acquisitionUnit);

        $this->assertEquals($acquisitionUnit->getState(), AcquisitionUnitOperatingState::OUT_OF_SERVICE->value);
    }

    public function testRoomComfortOk()
    {
        self::bootKernel();

        $container = static::getContainer();

        $InvalidDate = date('Y-m-d H:i:s', time() - 960);
        $this->createGetDataMockWithLastValueByType($container, array(
            array(20, $InvalidDate),
            array(40, $InvalidDate),
            array(400, $InvalidDate)));

        $acquisitionUnit = new AcquisitionUnit();
        $acquisitionUnit->setState(AcquisitionUnitOperatingState::OPERATIONAL->value);
        $acquisitionUnit->setName('ESP-TEST');

        $dataManager = $container->get(DataManagerInterface::class);

        $validData = array('temp' => 'OK', 'hum' => 'OK', 'co2' => 'OK');
        $this->assertEquals($dataManager->getRoomComfort($acquisitionUnit), $validData);
    }

    public function testRoomComfortBad()
    {
        self::bootKernel();

        $container = static::getContainer();

        $InvalidDate = date('Y-m-d H:i:s', time() - 960);
        $this->createGetDataMockWithLastValueByType($container, array(
            array(22, $InvalidDate),
            array(75, $InvalidDate),
            array(1200, $InvalidDate)));

        $acquisitionUnit = new AcquisitionUnit();
        $acquisitionUnit->setState(AcquisitionUnitOperatingState::OPERATIONAL->value);
        $acquisitionUnit->setName('ESP-TEST');

        $dataManager = $container->get(DataManagerInterface::class);

        $validData = array('temp' => 'Très mauvais', 'hum' => 'Très mauvais', 'co2' => 'Mauvais');
        $this->assertEquals($dataManager->getRoomComfort($acquisitionUnit), $validData);
    }

    public function testRoomComfortVeryBad() // Trip
    {
        self::bootKernel();

        $container = static::getContainer();

        $InvalidDate = date('Y-m-d H:i:s', time() - 960);
        $this->createGetDataMockWithLastValueByType($container, array(
            array(22, $InvalidDate),
            array(75, $InvalidDate),
            array(2100, $InvalidDate)));

        $acquisitionUnit = new AcquisitionUnit();
        $acquisitionUnit->setState(AcquisitionUnitOperatingState::OPERATIONAL->value);
        $acquisitionUnit->setName('ESP-TEST');

        $dataManager = $container->get(DataManagerInterface::class);

        $validData = array('temp' => 'Très mauvais', 'hum' => 'Très mauvais', 'co2' => 'Très mauvais');
        $this->assertEquals($dataManager->getRoomComfort($acquisitionUnit), $validData);
    }

    public function createGetDataMockWithLastValueByType($container, array $data)
    {
        $getDataMock = $this->createMock(GetDataInterface::class);
        $getDataMock->expects($this->exactly(3))
            ->method('getLastValueByType')
            ->willReturnOnConsecutiveCalls(
                $data[0],
                $data[1],
                $data[2]
            );
        $container->set(GetDataInterface::class, $getDataMock);
    }


}