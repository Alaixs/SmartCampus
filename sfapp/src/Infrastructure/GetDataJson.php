<?php

namespace App\Infrastructure;

use App\Domain\GetDataInteface;

class GetDataJson implements GetDataInteface
{
    public array $allValues;

    public function filterArrayByOne($array, $key, $value) : array
    {
        return array_filter($array, function ($v) use ($key, $value) {
            return isset($v[$key]) && str_contains($v[$key], $value);
        });
    }

    public function getLastValueByType($room, $type): array
    {
        if (file_exists(__DIR__ . '/data.json')) {
            $json = file_get_contents(__DIR__ . '/data.json');
            $data = json_decode($json, true);

            $this->allValues = $this->filterArrayByOne($data, 'nom', $type);
            $this->allValues = $this->filterArrayByOne($this->allValues, 'localisation', $room->getName());
            usort($this->allValues, function ($a, $b) {
                return strtotime($b['dataCapture']) - strtotime($a['dataCapture']);
            });
            if (!empty($this->allValues)) {
                $lastValue = $this->allValues[0]['valeur'];
                $lastValueDate = $this->allValues[0]['dataCapture'];
                return [$lastValue, $lastValueDate];
            }

        }

        return [-1, 0];
    }

}