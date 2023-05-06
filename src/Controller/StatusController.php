<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;

class StatusController extends AbstractController
{
    #[Route('/status/{id}', name: 'app_status')]
    public function index(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        
        $myEntity = $entityManager->getRepository(Reclamation::class)->find($id);
    if (!$myEntity) {
        throw $this->createNotFoundException('No entity found for id ' . $id);
    }
    if($myEntity->getetat()=='en cours')
    {
        $myEntity->setetat('valide');
    }
    else 
    {
        $myEntity->setetat('en cours');
    }
    
    $entityManager->persist($myEntity);
    $entityManager->flush();

    return $this->redirectToRoute('app_reclamation_index_admin', ['id' => $myEntity->getId()]);
    }

    
}
