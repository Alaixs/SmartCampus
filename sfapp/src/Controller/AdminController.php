<?php

namespace App\Controller;

use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use App\Form\AddRoomFormType;
use App\Form\AddSaFormType;
use App\Form\AssignFormType;
use App\Form\RemoveSAFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminController extends AbstractController
{

    #[Route('/admin', name: 'app_admin')]
    public function admin(?int $id, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $repository = $entityManager->getRepository('App\Entity\Room');
        $rooms = $repository->findAll();

        $user = 'admin';

        return $this->render('admin/index.html.twig', [
            'user' => $user,
            'controller_name' => 'IndexController',
            'listRooms' => $rooms,
            'id' => $id,
        ]);
    }
}

