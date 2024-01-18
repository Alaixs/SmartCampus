<?php

namespace App\Model;

use DateTime;

class GraphData
{

    public string $type;

    public DateTime $startDate;

    public DateTime $endDate;

    public string $period;

    public function getType(): string
    {
        return $this->type;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

}
