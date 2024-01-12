<?php

namespace App\Domain;

use App\Entity\Room;

interface GetDataInteface
{
    public function getLastValueByType(Room $room, $type): array;
    public function getRoomsComfortIndicator(array $rooms) : array;

}
