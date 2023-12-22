<?php

namespace App\Controller;

use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ClientController extends AbstractController
{
    #[Route('/roomSelection/{floor<\d+>?0}', name: 'app_client')]
    public function index(int $floor, BuildingRepository $buildingRepository, RoomRepository $roomRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $building = $buildingRepository->findOneBy(['name' => 'Département informatique']);

        $room = $roomRepository->findAll();

        $roomsQuery = $roomRepository->findRoomsByFloor($floor);

        $pagination = $paginator->paginate(
            $roomsQuery,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('client/index.html.twig', [
            'building' => $building,
            'pagination' => $pagination,
            'floor' => $floor,
            'room' => $room
        ]);
    }

    #[Route('/viewData/{room}', name: 'view_data')]
    public function viewData(): Response
    {

        return $this->render('client/viewData.html.twig', [
        ]);
    }
}
