<?php

namespace App\Controller;

use App\Domain\AcquisitionUnitOperatingState;
use App\Domain\DataManagerInterface;
use App\Entity\AcquisitionUnit;
use App\Form\AddAcquisitionUnitFormType;
use App\Form\RemoveAcquisitionUnitFormType;

use App\Repository\AcquisitionUnitRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AcquisitionUnitController extends AbstractController
{
    #[Route('/addAcquisitionUnit', name: 'addAU')]
    public function addAcquisitionUnit(Request $request, EntityManagerInterface $entityManager, AcquisitionUnitRepository $acquisitionUnitRepository, ValidatorInterface $validator): Response
    {
        $acquisitionUnit = new AcquisitionUnit();
        $acquisitionUnit->setState(AcquisitionUnitOperatingState::WAITING_FOR_ASSIGNMENT->value);

        $form = $this->createForm(AddAcquisitionUnitFormType::class, $acquisitionUnit);
        $form->handleRequest($request);

        $acquisitionUnitList = $acquisitionUnitRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier la contrainte UniqueEntity manuellement
            $validationErrors = $validator->validate($acquisitionUnit);

            if (count($validationErrors) > 0) {
                // Il y a des erreurs de validation (doublon de nom)
                foreach ($validationErrors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            } else {
                // Pas d'erreurs de validation, persistez l'entité
                $entityManager->persist($acquisitionUnit);
                $entityManager->flush();

                $this->addFlash('message', 'Le SA ' . $acquisitionUnit->getName() . ' a bien été ajouté.');
                return $this->redirectToRoute('addAU');
            }
        }

        return $this->render('acquisition_unit/addAcquisitionUnitForm.html.twig', [
            'addAcquisitionUnitForm' => $form,
            'acquisitionUnitList' => $acquisitionUnitList,
        ]);
    }

    #[Route('/removeAcquisitionUnit/{acquisitionUnit?}', name: 'removeAU')]
    public function removeAcquisitionUnit(Request $request, EntityManagerInterface $entityManager, RoomRepository $roomRepository, AcquisitionUnitRepository $acquisitionUnitRepository): Response
    {
        $acquisitionUnit = new AcquisitionUnit();

        $form = $this->createForm(RemoveAcquisitionUnitFormType::class, $acquisitionUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $au = $acquisitionUnitRepository->findOneBy(array('name' => $acquisitionUnit->getName()));

            if (!$au) {
                throw $this->createNotFoundException('L\'entité AcquisitionUnit avec l\'ID ' . $au . ' n\'existe pas.');
            }

            $room = $roomRepository->findOneBy(array('acquisitionUnit' => $au));


            if ($room) {
                $this->addFlash('error', 'Impossible de supprimer le SA ' . $au . ' car il est assigné à une salle.');
                return $this->redirectToRoute('removeAU');
            }

            $this->addFlash('message', 'Le SA ' . $au . ' a bien été supprimé.');


            $entityManager->remove($au);
            $entityManager->flush();

            $acquisitionUnitList = $acquisitionUnitRepository->findAll();

            if(empty($acquisitionUnitList)) {
                return $this->redirectToRoute('app_admin');
            }
            else {
                return $this->redirectToRoute('removeAU');

            }

        }

        return $this->render('acquisition_unit/removeAcquisitionUnitForm.html.twig', [
            'removeAcquisitionUnitForm' => $form,
        ]);
    }

    #[Route('/manageAcquisitionUnit/{acquisitionUnit}', name: 'manageAcquisitionUnit')]
    public function manageAcquisitionUnit(AcquisitionUnit $acquisitionUnit, RoomRepository $roomRepository, DataManagerInterface $dataManager) : Response
    {
        $room = $roomRepository->findOneBy(array('acquisitionUnit' => $acquisitionUnit->getId()));

        $data = $dataManager->get($acquisitionUnit);

        return $this->render('acquisition_unit/manageAcquisitionUnit.html.twig', [
            'room' => $room,
            'temp' => $data['temp'],
            'humidity' => $data['hum'],
            'co2' => $data['co2']
        ]);
    }

    #[Route('defAcquisitionUnitSupport/{acquisitionUnit}', name: 'defAcquisitionUnitSupport')]
    public function defAcquisitionUnitSupport(AcquisitionUnit $acquisitionUnit, EntityManagerInterface $entityManager) : Response
    {
        $acquisitionUnit->setState('Pris en charge');
        $entityManager->persist($acquisitionUnit);
        $entityManager->flush();
        return $this->redirectToRoute('manageAcquisitionUnit', ['acquisitionUnit' => $acquisitionUnit->getId()]);
    }

}
