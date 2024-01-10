<?php

namespace App\Controller;


use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Repository\AcquisitionUnitRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    #[Route('/admin', name: 'app_admin')]
    public function admin(RoomRepository $roomRepository, AcquisitionUnitRepository $acquisitionUnitRepository, EntityManagerInterface $entityManager, ?AcquisitionUnit $acquisitionUnit = null): Response {
        $roomList = $roomRepository->findAll();
        $acquisitionUnitList = $acquisitionUnitRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'roomList' => $roomList,
            'acquisitionUnitList' => $acquisitionUnitList,
            'acquisitionUnit' => $acquisitionUnit
        ]);
    }
}

