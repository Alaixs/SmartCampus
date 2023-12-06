<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\AddRoomFormType;
use App\Form\AssignFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class RoomController extends AbstractController
{
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
        return $this->render('room/addRoomForm.html.twig', [
            'addRoomForm' => $form
        ]);
    }

    #[Route('/editRoom/{roomName}', name: 'editRoom')]
    public function editRoom(string $roomName, Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = $entityManager->getRepository('App\Entity\Room')->findOneBy(array('name' => $roomName));
        $form = $this->createForm(AddRoomFormType::class, $room);

        $form->handleRequest($request);

        if ($request->request->has('Supprimer')) {
            if($room->getSA() != null)
            {
                $room->getSA()->setState("En attente");
                $room->setSA(null);
            }
            $entityManager->remove($room);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }

        if ($form->isSubmitted() && $form->isValid()) {


                $entityManager->persist($room);
                $entityManager->flush();
                return $this->redirectToRoute('app_admin');

        }


        return $this->render('room/editRoom.html.twig', [
            'room' => $room,
            'addRoomForm' => $form
        ]);
    }

    #[Route('/assignSA/{roomName}', name: 'assignSA')]
    public function assignSAtoRoom(string $roomName, Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = $entityManager->getRepository('App\Entity\Room')->findOneBy(['name' => $roomName]);
        $form = $this->createForm(AssignFormType::class, $room);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newSA = $room->getSA();

            $oldSA = $entityManager->getUnitOfWork()->getOriginalEntityData($room)['SA'];

            if ($oldSA !== null) {
                $oldSA->setState('En attente');
                $entityManager->persist($oldSA);
            }

            $newSA->setState('En attente d\'installation');
            $entityManager->persist($newSA);
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('room/assignSAForm.html.twig', [
            'room' => $room,
            'assignSAForm' => $form,
        ]);
    }


    #[Route('/unAssignSA/{roomName}', name: 'unAssignSA')]
    public function unAssignSAtoRoom(string $roomName, Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = $entityManager->getRepository('App\Entity\Room')->findOneBy(array('name' => $roomName));
        if($room->getSA() != null)
        {
            $oldSA = $room->getSA();
            $room->setSA(null);
            $oldSA->setState('En attente');

            $entityManager->persist($oldSA);
            $entityManager->persist($room);

            $entityManager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }

}
