<?php

namespace App\Controller;

use App\Domain\StateSA;
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
            'listRooms' => $rooms,
        ]);
    }

    #[Route('/defSAoperationnel/{SA}', name: 'set_sa_to_op')]
    public function setSAToOp(AcquisitionUnit $SA, Request $request, EntityManagerInterface $entityManager): Response
    {

        $SA->setState(StateSA::OPERATIONNEL->value);
        $entityManager->flush();

        return $this->redirectToRoute('app_tech');
    }
}
