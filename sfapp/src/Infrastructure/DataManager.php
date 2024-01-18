<?php

namespace App\Infrastructure;

use App\Domain\AcquisitionUnitOperatingState;
use App\Domain\DataManagerInterface;
use App\Domain\GetDataInterface;
use App\Entity\AcquisitionUnit;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class DataManager implements DataManagerInterface
{
    private GetDataInterface $getData;
    private CacheInterface $cache;
    private EntityManagerInterface $entityManager;

    public function __construct(GetDataInterface $getData, CacheInterface $cache, EntityManagerInterface $entityManager)
    {
        $this->getData = $getData;
        $this->cache = $cache;
        $this->entityManager = $entityManager;
    }
    public function get(?AcquisitionUnit $acquisitionUnit, bool $isRealTime = false) : array
    {
        if($acquisitionUnit != null)
        {
            $cacheItem = $this->cache->getItem($acquisitionUnit->getName());
            if(!$cacheItem->isHit() || $isRealTime)
            {
                $data = $this->set($acquisitionUnit, $cacheItem);
            }
            else
            {
                $data = $cacheItem->get();
            }
            return $data;
        }
        return array('temp' => [-1, 0], 'hum' => [-1, 0], 'co2' => [-1, 0]);
    }

    public function getRoomComfort(AcquisitionUnit $acquisitionUnit) : array
    {
        $dateToDay = date('m:d');
        $dateMaxSummer =  date('m:d', 1725228004); // 1 Septembre
        $dateMinSummer = date('m:d', 1717192999); // 31 mai
        if($dateToDay > $dateMinSummer and $dateToDay < $dateMaxSummer)
        {
            $maxTemp = 28;
        }
        else
        {
            $maxTemp = 21;
        }

        $types = ['temp' => ['name' => 'temp', 'minMedium' => 17, 'maxMedium' => $maxTemp, 'min' => 17, 'max' => $maxTemp],
            'hum' => ['name' => 'hum', 'minMedium' => 0, 'maxMedium' => 70, 'min' => 0, 'max' => 70],
            'c02' => ['name' => 'co2', 'minMedium' => 0, 'maxMedium' => 1000, 'min' => 0, 'max' => 1500]];

        $roomComfortIndicator = array();

        $roomData = $this->get($acquisitionUnit);
        foreach($types as $type) {
            $value = $roomData[$type['name']][0];
            if ($value == -1) {
                $comfort = "Aucune données";
            } elseif ($value > 70 and $type == 'hum' and $roomData['temp'][0] > 20) {
                $comfort = "Très mauvais";
            } elseif (($value > $type['max'] or $value < $type['min']) and $type != 'hum') {
                $comfort = "Très mauvais";
            } elseif ($value > $type['maxMedium'] or $value < $type['minMedium']) {
                $comfort = 'Mauvais';
            } else {
                $comfort = 'OK';
            }
            $roomComfortIndicator[$type['name']] = $comfort;
        }

        return $roomComfortIndicator;

    }

    public function getRoomsComfort(array $acquisitionUnits) : array
    {
        $roomsComfort = array();
        foreach($acquisitionUnits as $acquisitionUnit)
        {
            $roomsComfort[$acquisitionUnit->getName()] = $this->getRoomComfort($acquisitionUnit);
        }
        return $roomsComfort;
    }

    public function set(AcquisitionUnit $acquisitionUnit, CacheItemInterface $cacheItem) : array
    {
        $temp = $this->getData->getLastValueByType($acquisitionUnit, 'temp');
        $humidity = $this->getData->getLastValueByType($acquisitionUnit, 'hum');
        $co2 = $this->getData->getLastValueByType($acquisitionUnit, 'co2');
        $data = ['temp' => $temp, 'hum' => $humidity, 'co2' => $co2];

        $currentTimestamp = time() + 3600;

        // Checks if no data is bad and if data is not too dated (more than 15 minutes)
        if ((($temp[0] == -1 || $humidity[0] == -1 || $co2[0] == -1 || $currentTimestamp - strtotime($temp[1]) > 600 || $currentTimestamp - strtotime($humidity[1]) > 600 || $currentTimestamp - strtotime($co2[1]) > 600))
            && $acquisitionUnit->getState() == AcquisitionUnitOperatingState::OPERATIONAL->value) {

            $acquisitionUnit->setState(AcquisitionUnitOperatingState::OUT_OF_SERVICE->value);
            $this->entityManager->persist($acquisitionUnit);
            $this->entityManager->flush();
        }
        // Checks data for outliers
        else if($temp[0] > 50 || $humidity[0] > 100 || $co2[0] > 6000)
        {
            $acquisitionUnit->setState(AcquisitionUnitOperatingState::FAILURE->value);
            $this->entityManager->persist($acquisitionUnit);
            $this->entityManager->flush();
        }
        $cacheItem->expiresAfter(300);
        $cacheItem->set($data);
        $this->cache->save($cacheItem);

        return $data;
    }

    public function getLastValuesWithLimit(?AcquisitionUnit $acquisitionUnit, int $limit): array
    {
        $data = array();
        $types = array('temp', 'hum', 'co2');

        foreach($types as $type)
        {
            $data[$type] = $this->getData->getLastValuesWithLimit($acquisitionUnit, $type, $limit);
        }
        return $data;
    }

}