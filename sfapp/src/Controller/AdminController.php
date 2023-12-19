<?php

namespace App\Controller;


use App\Repository\AcquisitionUnitRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    #[Route('/admin', name: 'app_admin')]
    public function admin(RoomRepository $roomRepository, AcquisitionUnitRepository $acquisitionUnitRepository): Response
    {
        $rooms = $roomRepository->findAll();
        $sas = $acquisitionUnitRepository->findAll();

        $user = 'admin';

        return $this->render('admin/index.html.twig', [
            'user' => $user,
            'listRooms' => $rooms,
            'listSA' => $sas,
        ]);
    }
}

