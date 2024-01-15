<?php

namespace App\Controller;

use App\Domain\GetDataInteface;
use App\Entity\Room;
use App\Form\GraphFormType;
use App\Model\GraphData;
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

    #[Route('/viewData/{room}', name: 'view_data')]
    public function viewData(Room $room, GetDataInteface $getDataJson): Response
    {
        $temp = $getDataJson->getLastValueByType($room, 'temp');
        $humidity = $getDataJson->getLastValueByType($room, 'hum');
        $co2 = $getDataJson->getLastValueByType($room, 'co2');

        return $this->render('client/viewData.html.twig', [
            'room' => $room,
            'temp' => $temp,
            'humidity' => $humidity,
            'co2' => $co2,
        ]);
    }

    

    #[Route('/viewGraph/{room}/{type}/{period}/{startDate}/{endDate}', name: 'view_graph')]
    public function viewGraph(Room $room, $type, $period, $startDate, $endDate, Request $request, GetDataInteface $getDataJson): Response
    {

        $data = $getDataJson->getValuesByPeriod($room, $type, $period, $startDate, $endDate);

        $period = $request->query->get('period');

        $graphData = new GraphData();
        $form = $this->createForm(GraphFormType::class, $graphData);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $type = $graphData->getType();
            $startDate = $graphData->getStartDate()->format('Y-m-d');
            $endDate = $graphData->getEndDate()->format('Y-m-d');
            $period = $graphData->getPeriod();

            return $this->redirectToRoute('view_graph', [
                'room' => $room->getId(),
                'type' => $type,
                'period' => $period,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
        }
    
        return $this->render('client/viewGraph.html.twig', [
            'room' => $room,
            'data' => $data,
            'sensor' => $type,
            'period' => $period,
            'form' => $form->createView(),
        ]);

    }
}
