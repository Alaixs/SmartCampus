<?php

namespace App\Domain;

use App\Entity\AcquisitionUnit;
use Psr\Cache\CacheItemInterface;

interface DataManagerInterface
{
    public function get(AcquisitionUnit $acquisitionUnit, bool $isRealTime = false) : array;

    public function getRoomComfort(AcquisitionUnit $acquisitionUnit) : array;

    public function getRoomsComfort(array $acquisitionUnits) : array;

    public function set(AcquisitionUnit $acquisitionUnit, CacheItemInterface $cacheItem) : array;

    public function getLastValuesWithLimit(AcquisitionUnit $acquisitionUnit, int $limit): array;

}