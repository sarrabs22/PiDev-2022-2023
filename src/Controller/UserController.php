<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/', name: 'app_user')]
    public function index(UserRepository $rep2): Response
    {
        return $this->render('evenement/index.html.twig', [
            'users' => $rep2->findAll(),
        ]);
    }

   
}
