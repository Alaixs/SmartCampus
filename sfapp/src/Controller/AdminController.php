<?php

namespace App\Controller;


use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Form\SearchFormType;
use App\Model\SearchData;
use App\Repository\AcquisitionUnitRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    #[Route('/admin', name: 'app_admin')]
    public function admin(RoomRepository $roomRepository, AcquisitionUnitRepository $acquisitionUnitRepository, Request $request): Response
    {
        $roomList = $roomRepository->findAll();
        $acquisitionUnitList = $acquisitionUnitRepository->findAll();
        $formSubmitted = false;
        $filtersApplied = false;

        $searchData = new SearchData();
        $form = $this->createForm(SearchFormType::class, $searchData);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $formSubmitted = true;
            $roomList = $roomRepository->findBySearch($searchData);
            if(!empty($searchData->getQ()) or !empty($searchData->getFloors()) or !empty($searchData->getAcquisitionUnitState()))
            {
                $filtersApplied = true;
            }
            return $this->render('admin/index.html.twig', [
                'form' => $form->createView(),
                'acquisitionUnitList' => $acquisitionUnitList,
                'formSubmitted' => $formSubmitted,
                'roomList' => $roomList,
                'filtersApplied' => $filtersApplied, 
            ]);
        }


        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'roomList' => $roomList,
            'acquisitionUnitList' => $acquisitionUnitList,
            'formSubmitted' => $formSubmitted,
            'filtersApplied' => $filtersApplied,
        ]);
    }
}

