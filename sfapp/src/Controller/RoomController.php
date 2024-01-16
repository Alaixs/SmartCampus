<?php

namespace App\Controller;

use App\Domain\GetDataInteface;
use App\Entity\Room;
use App\Form\AddRoomFormType;
use App\Form\AssignAcquisitionUnitFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AcquisitionUnitRepository;
use App\Domain\AcquisitionUnitOperatingState;

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

            $this->addFlash('message', 'La salle ' . $room->getName() . ' a bien été ajoutée.');

            return $this->redirectToRoute('addRoom');

        }
        return $this->render('room/addRoomForm.html.twig', [
            'addRoomForm' => $form,
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
                return $this->redirectToRoute('roomDetail', ['room' => $room->getId()]);

        }


        return $this->render('room/editRoom.html.twig', [
            'room' => $room,
            'addRoomForm' => $form
        ]);
    }

    #[Route('/removeRoom/{room}', name: 'removeRoom')]
    public function removeRoom(Room $room, EntityManagerInterface $entityManager): Response
    {
            if($room->getAcquisitionUnit() != null)
            {
                $room->getAcquisitionUnit()->setState(AcquisitionUnitOperatingState::WAITING_FOR_ASSIGNMENT->value);
                $room->setAcquisitionUnit(null);
            }
            $entityManager->remove($room);
            $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }


    #[Route('/assignAcquisitionUnit/{room}', name: 'assignAU')]
    public function assignAcquisitionUnitToRoom(Room $room, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(AssignAcquisitionUnitFormType::class, $room);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newAcquisitionUnit = $room->getAcquisitionUnit();

            $oldAcquisitionUnit = $entityManager->getUnitOfWork()->getOriginalEntityData($room)['acquisitionUnit'];

            if ($oldAcquisitionUnit !== null) {
                $oldAcquisitionUnit->setState(AcquisitionUnitOperatingState::WAITING_FOR_ASSIGNMENT->value);
                $entityManager->persist($oldAcquisitionUnit);
            }

            $newAcquisitionUnit->setState(AcquisitionUnitOperatingState::WAITING_FOR_INSTALLATION->value);
            $entityManager->persist($newAcquisitionUnit);
            $entityManager->persist($room);
            $entityManager->flush();
            $this->addFlash('message', "Le système d'acquisition " . $newAcquisitionUnit->getName() . "a bien été affecter à la salle " . $room->getName());

            return $this->redirectToRoute('manageAcquisitionUnit', ['acquisitionUnit' => $room->getAcquisitionUnit()->getId()]);
        }

        return $this->render('room/assignAcquisitionUnitForm.html.twig', [
            'room' => $room,
            'assignAcquisitionUnitForm' => $form,
        ]);
    }


    #[Route('/unassignAcquisitionUnit/{room}', name: 'unassignAU')]
    public function unassignAcquisitionUnitFromRoom(Room $room, EntityManagerInterface $entityManager): Response
    {
        if($room->getAcquisitionUnit() != null)
        {
            $oldAcquisitionUnit = $room->getAcquisitionUnit();
            $room->setAcquisitionUnit(null);
            $oldAcquisitionUnit->setState(AcquisitionUnitOperatingState::WAITING_FOR_ASSIGNMENT->value);

            $entityManager->persist($oldAcquisitionUnit);
            $entityManager->persist($room);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/roomDetail/{room}', name: 'roomDetail')]
    public function roomDetail(Room $room, AcquisitionUnitRepository $acquisitionUnitRepository, GetDataInteface $getDataJson): Response
    {
        $hasAcquisitionUnitInDatabase = $acquisitionUnitRepository->count(array()) > 0;
        $hasAcquisitionUnitAvailable = $acquisitionUnitRepository->count(array('state' => "En attente d'affectation")) > 0;

        $temp = $getDataJson->getLastValueByType($room, 'temp');
        $humidity = $getDataJson->getLastValueByType($room, 'hum');
        $co2 = $getDataJson->getLastValueByType($room, 'co2');

        return $this->render('room/roomDetail.html.twig', [
            'room' => $room,
            'hasAcquisitionUnitAvailable' => $hasAcquisitionUnitAvailable,
            'hasAcquisitionUnitInDatabase' => $hasAcquisitionUnitInDatabase,
            'temp' => $temp,
            'humidity' => $humidity,
            'co2' => $co2
        ]);
    }
}
