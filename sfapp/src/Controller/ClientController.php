<?php

namespace App\Controller;

use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client/{floor<\d+>?0}', name: 'app_client')]
    public function index(int $floor, BuildingRepository $buildingRepository, RoomRepository $roomRepository): Response
    {
        $building = $buildingRepository->findOneBy(['name' => 'Département informatique']);
        $listRoom = $roomRepository->findBy(array('floor' => $floor));

        return $this->render('client/index.html.twig', [
            'building' => $building,
            'listRoom' => $listRoom,
            'floor' => $floor
        ]);
    }
}
