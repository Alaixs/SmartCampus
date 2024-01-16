<?php

namespace App\Domain;

use App\Entity\Room;

interface GetDataInteface
{
    public function getLastValueByType(Room $room, $type): array;
    public function getRoomComfortIndicator(Room $room) : array;

    public function getLastValue(Room $room) : array;
    public function getValuesByPeriod(Room $room, $type, $period, $startDate, $endDate): array;

}
