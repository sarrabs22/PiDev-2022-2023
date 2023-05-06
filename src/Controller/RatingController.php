<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AnnoncesRepository;
use Doctrine\ORM\EntityManagerInterface;

class RatingController extends AbstractController
{
    #[Route('/rating', name: 'app_rating')]
    public function index(Request $req, AnnoncesRepository $repo,EntityManagerInterface $em): Response
    {
        $annonceid =$req->get('id');
        $starvalue =$req->get('note_value');
        $userid= $req->get('userid');

        $annonce=$repo->find($annonceid);
       if($starvalue==1)
                 $annonce->setNombreEtoiles($annonce->getNombreEtoiles()+1);
        if($starvalue==2)
                 $annonce->setNombreEtoiles($annonce->getNombreEtoiles()+2);
        if($starvalue==3)
                 $annonce->setNombreEtoiles($annonce->getNombreEtoiles()+3);
        if($starvalue==4)
                 $annonce->setNombreEtoiles($annonce->getNombreEtoiles()+4);
        if($starvalue==5)
                 $annonce->setNombreEtoiles($annonce->getNombreEtoiles()+5);


        $annonce->setRated($userid);


        $em->persist($annonce);
        $em->flush();
        
        return $this->render('annonce_crud/show.html.twig', [
            'annonce' => $annonce
        ]);
    }
}
