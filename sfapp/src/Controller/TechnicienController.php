<?php

namespace App\Controller;

use App\Domain\StateSA;
use App\Entity\AcquisitionUnit;
use App\Form\SearchFormType;
use App\Model\SearchData;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TechnicienController extends AbstractController
{
    #[Route('/technicien', name: 'app_tech')]
    public function technicien(RoomRepository $roomRepository, Request $request): Response
    {
        $rooms = $roomRepository->findAll();
        $formSubmitted = false;
        $filtersApplied = false;

        $user = 'technicien';


        $searchData = new SearchData();
        $form = $this->createForm(SearchFormType::class, $searchData);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $rooms = $roomRepository->findBySearch($searchData);
            $formSubmitted = true;
            $rooms = $roomRepository->findBySearch($searchData);
            if(!empty($searchData->getQ()) or !empty($searchData->getFloors()) or !empty($searchData->getAcquisitionUnitState()))
            {
                $filtersApplied = true;
            }
            return $this->render('admin/index.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
                'allRooms' => $rooms,
                'formSubmitted' => $formSubmitted,
                'filtersApplied' => $filtersApplied,
            ]);
        }



        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'allRooms' => $rooms,
            'formSubmitted' => $formSubmitted,
            'filtersApplied' => $filtersApplied,
        ]);
    }

    #[Route('/defSAoperationnel/{SA}', name: 'set_sa_to_op')]
    public function setSAToOp(AcquisitionUnit $SA, Request $request, EntityManagerInterface $entityManager): Response
    {

        $SA->setState(StateSA::OPERATIONNEL->value);
        $entityManager->flush();

        return $this->redirectToRoute('app_tech');
    }
}
