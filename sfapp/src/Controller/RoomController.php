<?php

namespace App\Controller;

use App\Domain\GetDataInteface;
use App\Entity\Room;
use App\Form\AddRoomFormType;
use App\Form\AssignSAFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RoomRepository;
use App\Repository\AcquisitionUnitRepository;
use App\Domain\GetDataJson;
use App\Domain\StateSA;

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

        $showToast = false;


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($room);
            $entityManager->flush();

            $showToast = true;

            $entityManager->persist($room);
            //return $this->redirectToRoute('app_admin');
        }
        return $this->render('room/addRoomForm.html.twig', [
            'addRoomForm' => $form,
            'showToast' => $showToast,
        ]);
    }

    #[Route('/editRoom/{room}', name: 'editRoom')]
    public function editRoom(Room $room, Request $request, EntityManagerInterface $entityManager): Response
    {
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

    #[Route('/removeRoom/{room}', name: 'removeRoom')]
    public function removeRoom(Room $room, EntityManagerInterface $entityManager): Response
    {

        if($room)
        {
            if($room->getSA() != null)
            {
                $room->getSA()->setState(StateSA::ATTENTE_AFFECTATION->value);
                $room->setSA(null);
            }
            $entityManager->remove($room);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_admin');

    }


    #[Route('/assignSA/{room}', name: 'assignSA')]
    public function assignSAtoRoom(Room $room, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(AssignSAFormType::class, $room);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newSA = $room->getSA();

            $oldSA = $entityManager->getUnitOfWork()->getOriginalEntityData($room)['SA'];

            if ($oldSA !== null) {
                $oldSA->setState(StateSA::ATTENTE_AFFECTATION->value);
                $entityManager->persist($oldSA);
            }

            $newSA->setState(StateSA::ATTENTE_INSTALLATION->value);
            $entityManager->persist($newSA);
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('detailRoom', ['room' => $room->getId()]);
        }

        return $this->render('room/assignSAForm.html.twig', [
            'room' => $room,
            'assignSAForm' => $form,
        ]);
    }


    #[Route('/unAssignSA/{room}', name: 'unAssignSA')]
    public function unAssignSAtoRoom(Room $room, EntityManagerInterface $entityManager): Response
    {
        if($room->getSA() != null)
        {
            $oldSA = $room->getSA();
            $room->setSA(null);
            $oldSA->setState(StateSA::ATTENTE_AFFECTATION->value);

            $entityManager->persist($oldSA);
            $entityManager->persist($room);

            $entityManager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/detailRoom/{room}', name: 'detailRoom')]
    public function detailRoom(Room $room, RoomRepository $RoomRepository, AcquisitionUnitRepository $SARepository, GetDataInteface $getDataJson): Response
    {
        $hasSAInDatabase = $SARepository->count(array()) > 0;
        $hasSAAvailable = $SARepository->count(array('state' => "En attente d'affectation")) > 0;

        $temp = $getDataJson->getLastValueByType($room->getName(), 'temp');
        $humidity = $getDataJson->getLastValueByType($room->getName(), 'humidity');
        $co2 = $getDataJson->getLastValueByType($room->getName(), 'co2');


        return $this->render('room/detailRoom.html.twig', [
            'room' => $room,
            'hasSAAvailable' => $hasSAAvailable,
            'hasSAInDatabase' => $hasSAInDatabase,
            'temp' => $temp,
            'humidity' => $humidity,
            'co2' => $co2
        ]);
    }

}
