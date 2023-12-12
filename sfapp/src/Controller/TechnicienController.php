<?php

namespace App\Controller;

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
    public function technicien(?int $id, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $repository = $entityManager->getRepository('App\Entity\Room');
        $rooms = $repository->findAll();

        $user = 'technicien';
        return $this->render('admin/index.html.twig', [
            'user' => $user,
            'listRooms' => $rooms,
            'id' => $id,
        ]);
    }

    #[Route('/defSAoperationnel/{idSA}', name: 'set_sa_to_op')]
    public function setSAToOp(int $idSA, Request $request, EntityManagerInterface $entityManager): Response
    {

        $idSA = $entityManager->getRepository('App\Entity\AcquisitionUnit')->findOneBy(array('id' => $idSA));

        $idSA->setState("Operationnel");
        $entityManager->flush();

        return $this->redirectToRoute('app_tech');
    }
}
