<?php

namespace App\Controller;

use App\Domain\StateSA;
use App\Entity\AcquisitionUnit;
use App\Form\AddSaFormType;
use App\Form\RemoveSAFormType;

use App\Repository\AcquisitionUnitRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcquisitionUnitController extends AbstractController
{
    #[Route('/addSA', name: 'addSA')]
    public function addSA(Request $request, EntityManagerInterface $entityManager, AcquisitionUnitRepository $acquisitionUnitRepository): Response
    {
        $sa = new AcquisitionUnit();
        $sa->setState(StateSA::ATTENTE_AFFECTATION->value);

        $form = $this->createForm(AddSaFormType::class, $sa);
        $form->handleRequest($request);

        $listeSa = $acquisitionUnitRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sa);
            $entityManager->flush();

            return $this->redirectToRoute('addSA');

        }

        return $this->render('acquisition_unit/addSAForm.html.twig', [
            'addSAForm' => $form,
            'listeSa' => $listeSa,
        ]);
    }

    #[Route('/removeSA/{sa?}', name: 'removeSA')]
    public function removeSA(Request $request, EntityManagerInterface $entityManager, RoomRepository $roomRepository, AcquisitionUnitRepository $acquisitionUnitRepository, ?AcquisitionUnit $sa): Response
    {
        $sa = new AcquisitionUnit();

        $form = $this->createForm(RemoveSAFormType::class, $sa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $saId = $form->get('number')->getData();

            $sa = $acquisitionUnitRepository->find($saId);

            if (!$sa) {
                throw $this->createNotFoundException('L\'entité AcquisitionUnit avec l\'ID ' . $saId . ' n\'existe pas.');
            }

            $room = $roomRepository->findOneBy(array('SA' => $sa));


            if ($room) {
                $this->addFlash('error', 'Impossible de supprimer le SA ' . $saId . ' car il est assigné à une salle.');
                return $this->redirectToRoute('removeSA');
            }

            $this->addFlash('message', 'Le SA ' . $saId . ' a bien été supprimé.');

            $entityManager->remove($sa);
            $entityManager->flush();

            $listSA = $acquisitionUnitRepository->findAll();

            if(empty($listSA)) {
                return $this->redirectToRoute('app_admin');
            }
            else {
                return $this->redirectToRoute('removeSA');

            }

        }

        return $this->render('acquisition_unit/removeSAForm.html.twig', [
            'removeSAForm' => $form,
        ]);
    }

}
