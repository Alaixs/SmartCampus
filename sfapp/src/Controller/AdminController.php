<?php

namespace App\Controller;

use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Form\AddRoomFormType;
use App\Form\AddSaFormType;
use App\Form\AssignFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function index(): Response
    {

        return $this->render('index.html.twig', []);
    }

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

        #[Route('/admin', name: 'app_admin')]
    public function admin(?int $id, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $repository = $entityManager->getRepository('App\Entity\Room');
        $rooms = $repository->findAll();

        $user = 'admin';

        return $this->render('admin/index.html.twig', [
            'user' => $user,
            'controller_name' => 'IndexController',
            'listRooms' => $rooms,
            'id' => $id,
        ]);
    }
    

    #[Route('/addRoom', name: 'addRoom')]
    public function addRoom(Request $request, EntityManagerInterface $entityManager): Response
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
    public function editRoom(string $roomName, Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = $entityManager->getRepository('App\Entity\Room')->findOneBy(array('name' => $roomName));
        $form = $this->createForm(AddRoomFormType::class, $room)
            ->add('Supprimer', SubmitType::class, [
                'attr' => ['class' => 'deleteButton']
            ]);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('Supprimer')->isClicked())
            {
                $entityManager->remove($room);
                $entityManager->flush();
                return $this->redirectToRoute('app_admin');
            }
            else
            {
                $entityManager->persist($room);
                $entityManager->flush();
                return $this->redirectToRoute('app_admin');
            }
        }


        return $this->render('admin/editRoom.html.twig', [
            'room' => $room,
            'addRoomForm' => $form
        ]);
    }


    #[Route('/addSA', name: 'addSA')]
    public function addSA(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
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

    #[Route('/assignSA/{roomName}', name: 'assignSA')]
    public function assignSAtoRoom(string $roomName, Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = $entityManager->getRepository('App\Entity\Room')->findOneBy(array('name' => $roomName));
        $form = $this->createForm(AssignFormType::class, $room);

        $oldSA = $room->getSA();

        $form->handleRequest($request);

        if ($request->request->has('test')) {
            $room->setSA(null);
            $oldSA->setState('En attente');
            $entityManager->persist($room);
            $entityManager->persist($oldSA);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');

        }
        if ($form->isSubmitted() && $form->isValid()) {

            $newSA = $room->getSA();

            if ($oldSA) {
                $oldSA->setState('En attente');
                $entityManager->persist($oldSA);
            }

            $newSA->setState('SA attribué');
            $entityManager->persist($newSA);

            $entityManager->persist($room);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/assignSAForm.html.twig', [
            'room' => $room,
            'assignSAForm' => $form,
        ]);
    }
}

