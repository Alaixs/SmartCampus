<?php

namespace App\Domain;

use App\Entity\AcquisitionUnit;
use App\Entity\Room;

interface GetDataInterface
{
    public function getLastValueByType(AcquisitionUnit $acquisitionUnit, $type): array;
    public function getLastValue(AcquisitionUnit $acquisitionUnit) : array;
    public function getValuesByPeriod(Room $room, $type, $period, $startDate, $endDate): array;

}
