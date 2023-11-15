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
        $room = new Room();
    
        $form = $this->createForm(AddRoomFormType::class, $room);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            $errors = $validator->validate($room);
    
            if (count($errors) > 0) {
                $response = $this->render('admin/index.html.twig', [
                    'controller_name' => 'IndexController',
                    'listRooms' => $rooms,
                    'id' => $id,
                    'addRoomForm' => $form,
                    'errors' => $errors,
                ]);
    
                $response->setContent($response->getContent() . "<script>togglePopup();</script>");
    
                return $response;
            }
    
            if ($form->isValid()) {
                $entityManager->persist($room);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_admin');
            }
        }
    
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'IndexController',
            'listRooms' => $rooms,
            'id' => $id,
//            'addRoomForm' => $form,
        ]);
    }
    

    #[Route('/addRoom', name: 'addRoom')]
    public function newRoom(Request $request, EntityManagerInterface $entityManager): Response
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


    #[Route('/modifRoom/{roomName}', name: 'addRoom')]
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

        return $this->render('admin/modifRoom.html.twig', [
            'room' => $room,
            'addRoomForm' => $form


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

        }

        return $this->render('partial/popUpAddSAForm.html.twig', [
            'addSAForm' => $form,
            'listeSa' => $listeSa,
        ]);
    }
}

