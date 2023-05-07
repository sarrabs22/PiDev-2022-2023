<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
class UserprofileController extends AbstractController
{
    #[Route('/userprofile', name: 'app_userprofile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        //getting user
        $user=$this->getUser();
        
        /////
        // Retrieve the user entity from the database
        $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['id' => $user->getUserIdentifier()]);
// Convert the image data to a base64 string
$base64 = base64_encode($user->getImage());
        return $this->render('userprofile/index.html.twig', [
            'controller_name' => 'UserprofileController',
            'user' => $user,
            'image' => 'data:image/jpeg;base64,' . $base64,
        ]);
    }
}