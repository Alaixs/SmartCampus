<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(?int $id, ManagerRegistry $doctrine, ManagerRegistry $room): Response
    {
        $entityManager = $doctrine->getManager();
        $repository = $entityManager->getRepository('App\Entity\Admin');
        $room = $repository->find($id);
        $roomName=$room->getSessions();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'IndexController',
            'givenRoom' => $room,
            'roomName' => $roomName,
            'id' => $id,
        ]);
    }
}
