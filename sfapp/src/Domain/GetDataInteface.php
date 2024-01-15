<?php

namespace App\Domain;

use App\Entity\Room;

interface GetDataInteface
{
    public function getLastValueByType(Room $room, $type): array;
    public function getValuesByPeriod(Room $room, $type, $period, $startDate, $endDate): array;

}
