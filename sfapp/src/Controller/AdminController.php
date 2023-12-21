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

        $user = 'admin';

        $searchData = new SearchData();
        $form = $this->createForm(SearchFormType::class, $searchData);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $rooms = $roomRepository->findBySearch($searchData);

            return $this->render('admin/index.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
                'listRooms' => $rooms,
                'listSA' => $sas,
            ]);
        }


        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'listRooms' => $rooms,
            'listSA' => $sas,
        ]);
    }
}

