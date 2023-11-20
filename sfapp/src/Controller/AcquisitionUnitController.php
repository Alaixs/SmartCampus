<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcquisitionUnitController extends AbstractController
{
    #[Route('/acquisition/unit', name: 'app_acquisition_unit')]
    public function index(): Response
    {
        return $this->render('acquisition_unit/index.html.twig', [
            'controller_name' => 'AcquisitionUnitController',
        ]);
    }
}
