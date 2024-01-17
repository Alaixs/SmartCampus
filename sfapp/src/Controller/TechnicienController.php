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
    #[Route('/technicien', name: 'app_tech')]
    public function technicien(RoomRepository $roomRepository, Request $request): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchFormType::class, $searchData);
        $form->handleRequest($request);

        $rooms = $roomRepository->findAll();
        $formSubmitted = false;
        $filtersApplied = false;
        $user = 'technicien';

        if ($form->isSubmitted() && $form->isValid()) {
            $formSubmitted = true;
            $rooms = $roomRepository->findBySearch($searchData);

            if (!empty($searchData->getQ()) || !empty($searchData->getFloors()) || !empty($searchData->getAcquisitionUnitState())) {
                $filtersApplied = true;
            }
        }

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'allRooms' => $rooms,
            'formSubmitted' => $formSubmitted,
            'filtersApplied' => $filtersApplied,
        ]);
    }


    #[Route('/defAcquisitionUnitOperational/{acquisitionUnit}/{dataCounter<\d+>?0}', name: 'defAcquisitionUnitOperational')]
    public function manageAcquisitionUnit(int $dataCounter, Room $room, GetDataInteface $getDataJson, AcquisitionUnit $acquisitionUnit): Response
    {
        $defaultDataCounterValue = 0;

        $temp = $getDataJson->getLastFiveValues($room, 'temp');
        $humidity = $getDataJson->getLastFiveValues($room, 'hum');
        $co2 = $getDataJson->getLastFiveValues($room, 'co2');

        return $this->render('technicien/manageSA.html.twig', [
            'room' => $room,
            'temp' => $temp,
            'humidity' => $humidity,
            'co2' => $co2,
            'acquisitionUnit' => $acquisitionUnit,
            'dataCounter' => $dataCounter,
            'defaultDataCounterValue' => $defaultDataCounterValue
        ]);
    }


    #[Route('/setAcquisitionUnitOperational/{acquisitionUnit}', name: 'setAcquisitionUnitOperational')]
    public function defAcquisitionUnitOperationnel(AcquisitionUnit $acquisitionUnit, EntityManagerInterface $entityManager): Response {
        
        $acquisitionUnit->setState(AcquisitionUnitOperatingState::OPERATIONAL->value);

        $entityManager->flush();
    
        return $this->redirectToRoute('manageAcquisitionUnit', ['acquisitionUnit' => $acquisitionUnit->getId()]);
    }
}