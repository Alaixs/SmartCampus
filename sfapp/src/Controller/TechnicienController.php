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
        $user = 'technicien';
        return $this->render('admin/index.html.twig', [
            'user' => $user,
            'listRooms' => $rooms,
        ]);
    }

    #[Route('/manageSA/{acquisitionUnit}', name: 'manage_acquisitionUnit')]
    public function manageSA(AcquisitionUnit $AU, Room $room, GetDataInteface $getDataJson): Response
    {

        return $this->render('technicien/manageSA.html.twig', [
            'room' => $room,

        ]);
    }


    #[Route('/setAcquisitionUnitOperational/{acquisitionUnit}', name: 'set_au_to_operational')]
    public function setAcquisitionUnitOperational(Room $room, GetDataInteface $getDataJson, AcquisitionUnit $acquisitionUnit, Request $request, EntityManagerInterface $entityManager): Response
    {

        $temp = $getDataJson->getLastValueByType($room->getName(), 'temp');
        $humidity = $getDataJson->getLastValueByType($room->getName(), 'humidity');
        $co2 = $getDataJson->getLastValueByType($room->getName(), 'co2');


        $acquisitionUnit->setState(AcquisitionUnitState::OPERATIONNEL->value);
        $entityManager->flush();
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