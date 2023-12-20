<?php

namespace App\Controller;

use App\Domain\GetDataInteface;
use App\Domain\StateSA;
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

    #[Route('/manageSA/{SA}', name: 'manage_sa')]
    public function manageSA(Room $room, GetDataInteface $getDataJson): Response
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

    #[Route('/defSAoperationnel/{room}', name: 'set_sa_to_op')]
    public function setSAToOp(Room $room, EntityManagerInterface $entityManager, GetDataInteface $getDataJson): Response
    {
        $temp = $getDataJson->getLastValueByType($room->getName(), 'temp');
        $humidity = $getDataJson->getLastValueByType($room->getName(), 'humidity');
        $co2 = $getDataJson->getLastValueByType($room->getName(), 'co2');

        if($temp[0] > 0 && $co2[0] > 0 && $humidity[0] > 0)
        {
            $room->getSA()->setState(StateSA::OPERATIONNEL->value);
            $entityManager->flush();
            return $this->redirectToRoute('app_tech');
        }
        return $this->redirectToRoute('test_data', ['SA' =>  $room->getSA()->getId()]);
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
