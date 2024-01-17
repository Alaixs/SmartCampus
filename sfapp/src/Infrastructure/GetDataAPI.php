<?php

namespace App\Infrastructure;

use App\Domain\GetDataInteface;
use App\Entity\Room;
use Symfony\Component\HttpClient\HttpClient;
use DateTime;
use Exception;

class GetDataAPI implements GetDataInteface
{
    private $client;
    private array $dbNames;
    public function __construct()
    {
        $this->client = HttpClient::create();
        $this->dbNames = [
            'ESP-001' => 'sae34bdk1eq1',
            'ESP-002' => 'sae34bdk1eq2',
            'ESP-003' => 'sae34bdk1eq3',
            'ESP-004' => 'sae34bdk2eq1',
            'ESP-005' => 'sae34bdk2eq2',
            'ESP-006' => 'sae34bdk2eq3',
            'ESP-007' => 'sae34bdl1eq1',
            'ESP-008' => 'sae34bdl1eq2',
            'ESP-009' => 'sae34bdl1eq3',
            'ESP-010' => 'sae34bdl2eq1',
            'ESP-011' => 'sae34bdl2eq2',
            'ESP-012' => 'sae34bdl2eq3',
            'ESP-013' => 'sae34bdm1eq1',
            'ESP-014' => 'sae34bdm1eq2',
            'ESP-015' => 'sae34bdm1eq3',
            'ESP-016' => 'sae34bdm2eq1',
            'ESP-017' => 'sae34bdm2eq2',
            'ESP-018' => 'sae34bdm2eq3'];
    }

    public function getLastValueByType(Room $room, $type): array
    {
        try {
            if($room->getAcquisitionUnit() != null)
                {

                $response = $this->client->request('GET', 'https://sae34.k8s.iut-larochelle.fr/api/captures/last', [
                    'headers' => [
                        'dbname' => $this->dbNames[$room->getAcquisitionUnit()->getName()],
                        'username' => 'l1eq3',
                        'userpass' => 'dagde4-puvtus-tyVvog',
                    ],
                    'query' => [
                        'nom' => $type,
                        'nomsa' => $room->getAcquisitionUnit()->getName()
                    ],
                ]);


                $responseContent = $response->toArray();
                $value = is_numeric($responseContent[0]['valeur']) ? (int)$responseContent[0]['valeur'] : -1;
                $date = $responseContent[0]['dateCapture'];
                return [$value, $date];
            }
        } catch (\Exception $e) {
            return [-1,0];
        }
        return [-1,0];
    }

    public function getLastValue(Room $room) : array
    {
        $roomData = array();
        $types = ['temp', 'co2', 'hum'];

        foreach($types as $type)
        {
            $roomData[$type] = $this->getLastValueByType($room, $type);
        }
        return $roomData;

    }

    public function getRoomComfortIndicator(Room $room) : array
    {
        $dateToDay = date('m:d');
        $dateMaxSummer =  date('m:d', 1725228004); // 1 Septembre
        $dateMinSummer = date('m:d', 1717192999); // 31 mai
        if($dateToDay > $dateMinSummer and $dateToDay < $dateMaxSummer)
        {
            $maxTemp = 28;
        }
        else
        {
            $maxTemp = 21;
        }

        $types = ['temp' => ['name' => 'temp', 'minMedium' => 17, 'maxMedium' => $maxTemp, 'min' => 17, 'max' => $maxTemp],
            'hum' => ['name' => 'hum', 'minMedium' => 0, 'maxMedium' => 70, 'min' => 0, 'max' => 70],
            'c02' => ['name' => 'co2', 'minMedium' => 0, 'maxMedium' => 1000, 'min' => 0, 'max' => 1500]];

        $roomComfortIndicator = array();

        $roomData = $this->getLastValue($room);
        foreach($types as $type) {
            $value = $roomData[$type['name']][0];
            if ($value == -1) {
                $comfort = "Aucune données";
            } elseif ($value > 70 and $type == 'hum' and $roomData['temp'][0] > 20) {
                $comfort = "Très mauvais";
            } elseif (($value > $type['max'] or $value < $type['min']) and $type != 'hum') {
                $comfort = "Très mauvais";
            } elseif ($value > $type['maxMedium'] or $value < $type['minMedium']) {
                $comfort = 'Mauvais';
            } else {
                $comfort = 'OK';
            }
            $roomComfortIndicator[$type['name']] = $comfort;
        }

        return $roomComfortIndicator;
    }
    
    public function getValuesByPeriod($room, $type, $period, $startDate, $endDate): array
    {
        try {
            if ($room->getAcquisitionUnit() != null) {
                $startDate = $startDate->format('Y-m-d');
                $endDate = $endDate->format('Y-m-d');
    
                $response = $this->client->request('GET', 'https://sae34.k8s.iut-larochelle.fr/api/captures/interval?nom=' . $type . '&date1=' . $startDate . '&date2=' . $endDate, [
                    'headers' => [
                        'dbname' => $this->dbNames[$room->getAcquisitionUnit()->getName()],
                        'username' => 'l1eq3',
                        'userpass' => 'dagde4-puvtus-tyVvog',
                    ],
                    'query' => [
                        'nom' => $type,
                        'nomsa' => $room->getAcquisitionUnit()->getName(),
                    ],
                ]);
    
                $responseContent = $response->toArray();
    
                $aggregatedData = [];
    
                foreach ($responseContent as $data) {
                    $captureDate = new DateTime($data['dateCapture']);
                    $formattedDate = $captureDate->format('Y-m-d H:00:00');
    
                    if ($period === 'day') {
                        $formattedDate = $captureDate->format('Y-m-d');
                        $aggregatedData[$formattedDate][] = (float)$data['valeur'];
                    } elseif ($period === 'week') {
                        $weekNumber = $captureDate->format('W');
                        $aggregatedData[$weekNumber][] = (float)$data['valeur'];
                    } elseif ($period === 'month') {
                        $monthYearKey = $captureDate->format('Y-m');
                        $aggregatedData[$monthYearKey][] = (float)$data['valeur'];
                    } elseif ($period === 'hour') {
                        $aggregatedData[$formattedDate][] = (float)$data['valeur'];
                    }
                }
    
                $averagedData = [];
                foreach ($aggregatedData as $key => $values) {
                    $averageValue = count($values) > 0 ? array_sum($values) / count($values) : 0;
                    $averagedData[] = [
                        'date' => $key,
                        'value' => $averageValue,
                    ];
                }
    
                return $averagedData;
            }
        } catch (Exception $e) {
            return [
                [
                    'date' => null,
                    'value' => -1,
                    'error' => $e->getMessage()
                ],
            ];
        }
    
        return [
            [
                'date' => null,
                'value' => 0,
            ],
        ];
    }

    public function getLastFiveValues($room, $type): array
    {
        try {
            if($room->getAcquisitionUnit() != null)
            {

                $response = $this->client->request('GET', 'https://sae34.k8s.iut-larochelle.fr/api/captures/last', [
                    'headers' => [
                        'dbname' => $this->dbNames[$room->getAcquisitionUnit()->getName()],
                        'username' => 'l1eq3',
                        'userpass' => 'dagde4-puvtus-tyVvog',
                    ],
                    'query' => [
                        'nom' => $type,
                        'nomsa' => $room->getAcquisitionUnit()->getName(),
                        'limit' => '5'
                    ],
                ]);

                $responseContent = $response->toArray();
                return $responseContent;
            }
        } catch (\Exception $e) {
            return [-1,0];
        }
        return [-1,0];
    }
}
    