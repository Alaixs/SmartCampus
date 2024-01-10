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

    #[Route('/admin/{acquisitionUnit?}', name: 'app_admin')]
    public function admin(RoomRepository $roomRepository, AcquisitionUnitRepository $acquisitionUnitRepository, ?AcquisitionUnit $acquisitionUnit = null, EntityManagerInterface $entityManager): Response {
        $roomList = $roomRepository->findAll();
        $acquisitionUnitList = $acquisitionUnitRepository->findAll();

        if ($acquisitionUnit) {
            $acquisitionUnit->setState(AcquisitionUnitState::OPERATIONNEL->value);
            $entityManager->flush();
        }

        return $this->render('admin/index.html.twig', [
            'roomList' => $roomList,
            'acquisitionUnitList' => $acquisitionUnitList,
            'acquisitionUnit' => $acquisitionUnit
        ]);
    }
}

