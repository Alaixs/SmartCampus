<?php

namespace App\Controller;

use App\Domain\GetDataInteface;
use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Form\RemoveSAFormType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class TechnicienController extends AbstractController
{
    #[Route('/technicien', name: 'app_tech')]
    public function technicien(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'listRooms' => $rooms,
        ]);
    }

    #[Route('/manageAcquisitionUnit/{acquisitionUnit}', name: 'manageAcquisitionUnit')]
    public function manageAcquisitionUnit(Room $room, GetDataInteface $getDataJson, AcquisitionUnit $acquisitionUnit): Response
    {

        $temp = $getDataJson->getLastValueByType($room->getName(), 'temp');
        $humidity = $getDataJson->getLastValueByType($room->getName(), 'humidity');
        $co2 = $getDataJson->getLastValueByType($room->getName(), 'co2');


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
}