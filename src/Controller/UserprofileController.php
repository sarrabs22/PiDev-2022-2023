<?php

namespace App\Controller;

use App\Entity\Reclamation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Association;
use App\Repository\AssociationRepository;
use App\Repository\MembreRepository;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
class UserprofileController extends AbstractController
{
    #[Route('/userprofile', name: 'app_userprofile')]
    public function index(MembreRepository $memRepo,AssociationRepository $assorepo ,Request $request, EntityManagerInterface $entityManager,ReclamationRepository $RecRepo): Response
    {

        //getting user
        $reclamation = new Reclamation();
        //$user=$this->getUser();
        
        /////
        // Retrieve the user entity from the database
        $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['id' => $this->getUser()->getUserIdentifier()]);

        $reclamations = $RecRepo->findBy(['user' => $this->getUser()]);

        $reclamationStatuses = [];
        $reclamationNames = [];
        $reclamationDates = [];
        foreach ($reclamations as $reclamation) {
            $reclamationStatuses[] = $reclamation->getEtat();
            $reclamationNames [] = $reclamation->getMotifDeReclamation();
            $reclamationDates [] = $reclamation->getDataReclamation();
        }

        ////association
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
       // dd( $associations);


     
// Convert the image data to a base64 string
$base64 = base64_encode($user->getImage());
        return $this->render('userprofile/index.html.twig', [
            'controller_name' => 'UserprofileController',
            'user' => $user,
            'image' => 'data:image/jpeg;base64,' . $base64,
            'reclamationStatuses' => $reclamationStatuses,
            'reclamationNames' =>   $reclamationNames,
            'reclamationDates' =>   $reclamationDates,
            'associations' => $associations
        ]);
    }
}
