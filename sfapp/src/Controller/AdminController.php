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
        $searchData = new SearchData();
        $form = $this->createForm(SearchFormType::class, $searchData);
        $form->handleRequest($request);

        $roomList = $roomRepository->findAll();
        $acquisitionUnitList = $acquisitionUnitRepository->findAll();
        $formSubmitted = false;
        $filtersApplied = false;

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
}
