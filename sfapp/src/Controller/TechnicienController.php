<?php

namespace App\Controller;

use App\Domain\AcquisitionUnitOperatingState;
use App\Domain\DataManagerInterface;
use App\Domain\GetDataInterface;
use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Form\SearchFormType;
use App\Infrastructure\DataManager;
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
    public function manageAcquisitionUnit(int $dataCounter, Room $room, DataManagerInterface $dataManager, AcquisitionUnit $acquisitionUnit): Response
    {
        $defaultDataCounterValue = 0;

        $data = $dataManager->get($acquisitionUnit);
        return $this->render('technicien/manageSA.html.twig', [
            'room' => $room,
            'temp' => $data['temp'],
            'humidity' => $data['hum'],
            'co2' => $data['co2'],
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