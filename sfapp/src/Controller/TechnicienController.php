<?php

namespace App\Controller;

use App\Domain\GetDataInteface;
use App\Domain\AcquisitionUnitOperatingState;
use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Form\SearchFormType;
use App\Model\SearchData;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TechnicienController extends AbstractController
{
    #[Route('/manageAcquisitionUnit/{acquisitionUnit}', name: 'manageAcquisitionUnit')]
    public function manageAcquisitionUnit(Room $room, GetDataInteface $getDataJson, AcquisitionUnit $acquisitionUnit): Response
    {

        $temp = $getDataJson->getLastValueByType($room, 'temp');
        $humidity = $getDataJson->getLastValueByType($room, 'hum');
        $co2 = $getDataJson->getLastValueByType($room, 'co2');


        return $this->render('technicien/manageSA.html.twig', [
            'room' => $room,
            'temp' => $temp,
            'humidity' => $humidity,
            'co2' => $co2,
            'acquisitionUnit' => $acquisitionUnit
        ]);
    }
    
    #[Route('/testData/{SA}', name: 'test_data')]
    public function testData(Room $room, GetDataInteface $getDataJson): Response
    {
        $temp = $getDataJson->getLastValueByType($room->getName(), 'temp');
        $humidity = $getDataJson->getLastValueByType($room->getName(), 'humidity');
        $co2 = $getDataJson->getLastValueByType($room->getName(), 'co2');


        return $this->render('technicien/manageSA.html.twig', [
            'room' => $room,
            'temp' => $temp,
            'humidity' => $humidity,
            'co2' => $co2
        ]);
    }

    #[Route('/defAcquisitionUnitOperationnel/{acquisitionUnit}', name: 'app_defAcquisitionUnitOperationnel')]
    public function defAcquisitionUnitOperationnel(AcquisitionUnit $acquisitionUnit, EntityManagerInterface $entityManager): Response {
        
        $acquisitionUnit->setState(AcquisitionUnitOperatingState::OPERATIONAL->value);

        $entityManager->flush();
    
        return $this->redirectToRoute('app_admin');
    }
}