<?php

namespace App\Controller;

use App\Entity\AcquisitionUnit;
use App\Form\AddSaFormType;
use App\Form\RemoveSAFormType;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcquisitionUnitController extends AbstractController
{
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

        return $this->render('acquisition_unit/addSAForm.html.twig', [
            'addSAForm' => $form,
            'listeSa' => $listeSa,
        ]);
    }

    #[Route('/removeSA', name: 'removeSA')]
    public function removeSA(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $sa = new AcquisitionUnit();

        $form = $this->createForm(RemoveSAFormType::class, $sa);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $saId = $form->get('number')->getData();

            $sa = $entityManager->getRepository(AcquisitionUnit::class)->find($saId);

            if (!$sa) {
                throw $this->createNotFoundException('L\'entité AcquisitionUnit avec l\'ID ' . $saId . ' n\'existe pas.');
            }

            $query = $entityManager->createQuery(
                'SELECT r
                FROM App\Entity\Room r
                WHERE r.SA = :saId'
            )->setParameter('saId', $saId);
    

            if ($query->getResult()) {
                $this->addFlash('error', 'Impossible de supprimer le SA ' . $saId . ' car il est assigné à une salle.');
                return $this->redirectToRoute('removeSA');
            }

            $this->addFlash('message', 'Le SA ' . $saId . ' a bien été supprimé.');

            $entityManager->remove($sa);
            $entityManager->flush();

            return $this->redirectToRoute('removeSA');
        }

        return $this->render('acquisition_unit/removeSAForm.html.twig', [
            'removeSAForm' => $form,
        ]);
    }

}
