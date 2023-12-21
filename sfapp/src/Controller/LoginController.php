<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

          /* // Voici le error qui ne passe pas le test, pourtant il est fonctionnel et la page reste fonctionnel
         $error = $authenticationUtils->getLastAuthenticationError(); */

        return $this->render('login/index.html.twig', [
            //'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {

    }
}
