<?php

namespace App\Controller;
use App\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class LikesController extends AbstractController
{

    #[Route('/likes/{id}/{id2}', name: 'app_likes')]
    public function index($id, $id2, ManagerRegistry $doctrine, UrlGeneratorInterface $urlGenerator): Response
    {   
        $entityManager=$this->getDoctrine()->getManager();
        //Je vais chercher le User qui m'interesse
        $comment=$entityManager->getRepository(Comments::class)->findOneById($id);
        $entityManager = $doctrine->getManager();
        //Je récupère le champ "Likes" pour le modifier
        $likes = $comment->getLikes();

        //Pour lui incrémenter un like à chaque clique
        $plusDeLikes = $likes + 1;

        //Je mets à jour mon champ la table User
        $comment->setLikes($plusDeLikes);
        $entityManager->flush();

        //Je redirige vers la page
        $url=$urlGenerator->generate('detail', ['id'=> $id2]);
        return $this->redirect($url);
    }
       
}
