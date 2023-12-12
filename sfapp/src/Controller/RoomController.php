<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\AddRoomFormType;
use App\Form\AssignFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RoomRepository;
use App\Repository\AcquisitionUnitRepository;
use App\Domain\GetDataJson;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/deleteRoom/{roomName}', name: 'deleteRoom')]
    public function deleteRoom(string $roomName, Request $request, EntityManagerInterface $entityManager): Response
    {

        $room = $entityManager->getRepository('App\Entity\Room')->findOneBy(array('name' => $roomName));

        if($room)
        {
            if($room->getSA() != null)
            {
                $room->getSA()->setState("En attente d'affectation");
                $room->setSA(null);
            }
            $entityManager->remove($room);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_admin');

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
                $oldSA->setState("En attente d'affectation");
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
            $oldSA->setState('En attente d\'affectation');

            $entityManager->persist($oldSA);
            $entityManager->persist($room);

            $entityManager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/detailRoom/{roomName}', name: 'detailRoom')]
    public function detailRoom(string $roomName, Request $request, RoomRepository $RoomRepository, AcquisitionUnitRepository $SARepository, GetDataJson $getDataJson): Response
    {
        $room = $RoomRepository->findOneBy(array('name' => $roomName));
        $hasSAInDatabase = $SARepository->count([]) > 0;
        $hasSAAvailable = $SARepository->count(array('state' => "En attente d'affectation")) > 0;

        $temp = $getDataJson->getLastValueByType($room->getName(), 'temp');
        $humidity = $getDataJson->getLastValueByType($room->getName(), 'humidity');
        $co2 = $getDataJson->getLastValueByType($room->getName(), 'co2');


        return $this->render('room/detailRoom.html.twig', [
            'room' => $room,
            'hasSAAvailable' => $hasSAAvailable,
            "hasSAInDatabase" => $hasSAInDatabase,
            'temp' => $temp,
            'humidity' => $humidity,
            'co2' => $co2
        ]);
    }

}
