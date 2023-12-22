<?php

namespace App\Controller;

use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TechnicienController extends AbstractController
{
    #[Route('/technicien', name: 'app_tech')]
    public function technicien(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();

        $user = 'technicien';
        return $this->render('admin/index.html.twig', [
            'user' => $user,
            'roomList' => $rooms,
        ]);
    }

    #[Route('/setAcquisitionUnitOperational/{acquisitionUnit}', name: 'set_au_to_operational')]
    public function setAcquisitionUnitOperational(AcquisitionUnit $acquisitionUnit, Request $request, EntityManagerInterface $entityManager): Response
    {

        $acquisitionUnit->setState(AcquisitionUnitState::OPERATIONNEL->value);
        $entityManager->flush();

        return $this->redirectToRoute('app_tech');
    }
}
