<?php

namespace App\Domain;

interface GetDataInteface
{
    public function getLastValueByType($roomId, $type): array;

}
