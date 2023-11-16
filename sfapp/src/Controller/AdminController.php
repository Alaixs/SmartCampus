<?php

namespace App\Controller;

use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Form\AddRoomFormType;
use App\Form\AddSaFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(?int $id, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $repository = $entityManager->getRepository('App\Entity\Room');
        $rooms = $repository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'IndexController',
            'listRooms' => $rooms,
            'id' => $id,
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
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/addRoomForm.html.twig', [
            'addRoomForm' => $form
        ]);
    }


    #[Route('/editRoom/{roomName}', name: 'editRoom')]
    public function modifiRoom(string $roomName, Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = $entityManager->getRepository('App\Entity\Room')->findOneBy(array('name' => $roomName));
        $form = $this->createForm(AddRoomFormType::class, $room);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($room);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/editRoom.html.twig', [
            'room' => $room,
            'addRoomForm' => $form
        ]);
    }


    #[Route('/addSA', name: 'addSA')]
    public function newSA(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $sa = new AcquisitionUnit();
        $sa->setState("En attente");

        $form = $this->createForm(AddSaFormType::class, $sa);
        $form->handleRequest($request);

        $SaManager = $doctrine->getManager();
        $repository = $SaManager->getRepository('App\Entity\AcquisitionUnit');
        $listeSa = $repository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sa);
            $entityManager->flush();

            return $this->redirectToRoute('addSA');

        }

        return $this->render('admin/addSAForm.html.twig', [
            'addSAForm' => $form,
            'listeSa' => $listeSa,
        ]);
    }

    #[Route('/assignSA', name: 'assignSA')]
    public function assignSAtoRoom(): Response
    {
        return $this->render('admin/assignSAForm.html.twig', [
        ]);
    }
}

