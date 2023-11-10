<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\AddRoomFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(?int $id, ManagerRegistry $doctrine, ManagerRegistry $room, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $repository = $entityManager->getRepository('App\Entity\Room');
        $rooms = $repository->findAll();
        $room = new Room();

        $form = $this->createForm(AddRoomFormType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($room);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'IndexController',
            'listRooms' => $rooms,
            'id' => $id,
            'addRoomForm' => $form
        ]);
    }

    #[Route('/addRoom', name: 'addRoom')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();

        $form = $this->createForm(AddRoomFormType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($room);
            $entityManager->flush();

        }

        return $this->render('partial/popUpAddRoomForm.html.twig', [
            'addRoomForm' => $form
        ]);
    }
}
