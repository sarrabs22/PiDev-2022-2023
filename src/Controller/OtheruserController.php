<?php

namespace App\Controller;

use App\Entity\Association;
use App\Repository\AssociationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class OtheruserController extends AbstractController
{
    #[Route('/otheruser/{id}', name: 'app_otheruser')]
    public function index($id,UserRepository $repo,AssociationRepository $assorepo,EntityManagerInterface $entityManager): Response
    {
        $user=$repo->find($id);

        $qb = $entityManager->createQueryBuilder();
        $qb->select('a.id')
           ->from('App\Entity\Association', 'a')
           ->join('a.Membres', 'm')
           ->where('m.User = :user')
           ->setParameter('user', $user);
        
        $result = $qb->getQuery()->getResult();
        
        $associationIds = array_map(function($row) {
            return $row['id'];
        }, $result);
        
        $associations = $assorepo->findBy(['id' => $associationIds]);

        return $this->render('otheruser/index.html.twig', [
            'controller_name' => 'OtheruserController',
            'otheruser'=> $user ,
            'associations' =>  $associations
        ]);
    }
}
