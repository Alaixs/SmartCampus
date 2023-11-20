<?php

namespace App\Controller;

use App\Entity\AcquisitionUnit;
use App\Form\AddSaFormType;
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

}
