<?php

namespace App\Model;

class SearchData
{
    /** @var string  */
    public string $q = '';

    /** @var array  */
    public array $floors = [];

    /** @var array  */
    public array $acquisitionUnitState = [];

    public function getQ(): string
    {
        return $this->q;
    }

    public function getFloors(): array
    {
        return $this->floors;
    }

    public function getAcquisitionUnitState(): array
    {
        return $this->acquisitionUnitState;
    }


}