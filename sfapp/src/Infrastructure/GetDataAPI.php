<?php

namespace App\Infrastructure;

use App\Domain\GetDataInteface;
use App\Entity\Room;
use Symfony\Component\HttpClient\HttpClient;

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

                $value = isset($responseContent[0]['valeur']) ? (int)$responseContent[0]['valeur'] : -1;
                $date = $responseContent[0]['dateCapture'];
                return [$value, $date];
            }
        } catch (\Exception $e) {
            return [-1, 0];
        }
        return [-1, 0];
    }

}