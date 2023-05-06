<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OtheruserController extends AbstractController
{
    #[Route('/otheruser/{id}', name: 'app_otheruser')]
    public function index($id,UserRepository $repo): Response
    {
        $user=$repo->find($id);

        return $this->render('otheruser/index.html.twig', [
            'controller_name' => 'OtheruserController',
            'otheruser'=> $user ,
        ]);
    }
}
