<?php

namespace App\Controller;


use App\Form\SearchFormType;
use App\Model\SearchData;
use App\Repository\AcquisitionUnitRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    #[Route('/admin', name: 'app_admin')]
    public function admin(RoomRepository $roomRepository, AcquisitionUnitRepository $acquisitionUnitRepository, Request $request): Response
    {
        $rooms = $roomRepository->findAll();
        $sas = $acquisitionUnitRepository->findAll();
        $formSubmitted = false;
        $filtersApplied = false;

        $user = 'admin';

        $searchData = new SearchData();
        $form = $this->createForm(SearchFormType::class, $searchData);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $formSubmitted = true;
            $rooms = $roomRepository->findBySearch($searchData);
            if(!empty($searchData->getQ()) or !empty($searchData->getFloors()) or !empty($searchData->getAcquisitionUnitState()))
            {
                $filtersApplied = true;
            }
            return $this->render('admin/index.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
                'listSA' => $sas,
                'formSubmitted' => $formSubmitted,
                'allRooms' => $rooms,
                'filtersApplied' => $filtersApplied,
            ]);
        }


        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'allRooms' => $rooms,
            'listSA' => $sas,
            'formSubmitted' => $formSubmitted,
            'filtersApplied' => $filtersApplied,
        ]);
    }
}

