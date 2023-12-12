<?php

namespace App\Domain;

class GetDataJson implements GetDataInteface
{

    public function sortArrayByTime($array)
    {
        return usort($array, function ($a, $b) {
            return strtotime($b['dataCapture']) - strtotime($a['dataCapture']);
        });
    }

    public function filterArrayByOne($array, $key, $value)
    {
        return array_filter($array, function ($v) use ($key, $value) {
            return isset($v[$key]) && strpos($v[$key], $value) !== false;
        });
    }

    public function getLastValueByType($roomName, $type): array
    {
        if (file_exists(__DIR__ . '/data.json')) {
            $json = file_get_contents(__DIR__ . '/data.json');
            $data = json_decode($json, true);

            $filteredByType = $this->filterArrayByOne($data, 'nom', $type);
            $allValues = $this->filterArrayByOne($filteredByType, 'localisation', $roomName);
            usort($allValues, function ($a, $b) {
                return strtotime($b['dataCapture']) - strtotime($a['dataCapture']);
            });

            if (!empty($allValues)) {
                $lastValue = $allValues[0]['valeur'];
                $lastValueDate = $allValues[0]['dataCapture'];
                return [$lastValue, $lastValueDate];
            }
        }

        return [-1];
    }

}