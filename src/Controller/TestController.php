<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $user=new User();
        
       
        $user=$this->getUser();

        /* $profilePicture = $user->getImage(); */

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
