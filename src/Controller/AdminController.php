<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function index(UserRepository $UserRepo): Response
    {
        return $this->render('admin/usertable.html.twig', [
            'controller_name' => 'AdminController',

            'user_tab' => $UserRepo->findAll(),
            
        ]);
    }
    #[Route('/admin/deleteuser/{id}', name: 'app_admin_delete')]
    public function DeleteUser(UserRepository $UserRepo,$id,EntityManagerInterface $entityManager): Response
    {


        $user = $entityManager
            ->getRepository(User::class)
            ->find($id);

         // Get the Doctrine entity manager
         $entityManager = $this->getDoctrine()->getManager();


         
         // Remove the user from the database
         $entityManager->remove($user);
         $entityManager->flush();


         // Redirect to the user list page
         return $this->redirectToRoute('app_admin');
    }
}
