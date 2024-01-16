<?php

namespace App\Controller;


use App\Domain\AcquisitionUnitState;
use App\Domain\GetDataInteface;
use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Form\SearchFormType;
use App\Model\SearchData;
use App\Repository\AcquisitionUnitRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends AbstractController
{

    #[Route('/admin', name: 'app_admin')]
    public function admin(UserInterface $user, RoomRepository $roomRepository, AcquisitionUnitRepository $acquisitionUnitRepository,
                           GetDataInteface $getData, Request $request): Response {
        $roomList = $roomRepository->findAll();
        $acquisitionUnitList = $acquisitionUnitRepository->findAll();
        $formSubmitted = false;
        $filtersApplied = false;
        $searchData = new SearchData();
        $form = $this->createForm(SearchFormType::class, $searchData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formSubmitted = true;
            $roomList = $roomRepository->findBySearch($searchData);

            if (!empty($searchData->getQ()) || !empty($searchData->getFloors()) || !empty($searchData->getAcquisitionUnitState())) {
                $filtersApplied = true;
            }
        }

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'acquisitionUnitList' => $acquisitionUnitList,
            'formSubmitted' => $formSubmitted,
            'roomList' => $roomList,
            'filtersApplied' => $filtersApplied,
        ]);
    }

    #[Route('/getRoomComfort/{room}', name: 'get_roomComfort')]
    public function getRoomsComfortJson(GetDataInteface $getData, Room $room): JsonResponse
    {
        $roomsComfortIndicator = $getData->getRoomComfortIndicator($room);

        return $this->json($roomsComfortIndicator);
    }
}
