<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(?int $id, ManagerRegistry $doctrine, ManagerRegistry $room): Response
    {
        $entityManager = $doctrine->getManager();
        $repository = $entityManager->getRepository('App\Entity\Room');
        $room = $repository->findAll();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'IndexController',
            'listRooms' => $room,
            'id' => $id,
        ]);
    }
}
