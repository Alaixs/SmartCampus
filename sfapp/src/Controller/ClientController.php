<?php

namespace App\Controller;

use App\Domain\GetDataInteface;
use App\Entity\Room;
use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ClientController extends AbstractController
{
    #[Route('/{floor<\d+>?0}', name: 'app_client')]
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
            'room' => $room,
        ]);
    }

    #[Route('/viewData/{room}/{valueSelected?temp}', name: 'view_data')]
    public function viewData(string $valueSelected, Room $room, GetDataInteface $getDataJson): Response
    {
        $currentValue = null;
        $defaultValueType = null;

        $valueType = ['temp', 'humidity', 'co2'];
        if (!in_array($valueSelected, $valueType)) {
            throw $this->createNotFoundException('Invalid value selected.');
        }

        switch ($valueSelected) {
            case 'temp':
                $currentValue = $getDataJson->getLastValueByType($room, 'temp');
                $defaultValueType = 0;
                break;
            case 'humidity':
                $currentValue = $getDataJson->getLastValueByType($room, 'hum');
                $defaultValueType = 1;
                break;
            case 'co2':
                $currentValue = $getDataJson->getLastValueByType($room, 'co2');
                $defaultValueType = 2;
                break;
        }

        return $this->render('client/viewData.html.twig', [
            'valueSelected' => $valueSelected,
            'room' => $room,
            'currentValue' => $currentValue,
            'valueType' => $valueType,
            'defaultValueType' => $defaultValueType
        ]);
    }
}
