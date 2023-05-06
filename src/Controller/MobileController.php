<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Evenement;
use App\Entity\User;
use App\Repository\EvenementRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
#[Route('/mobile')]
class MobileController extends AbstractController
{
    #[Route('/addEventJson', name: 'app_evenement_newJson', methods: ['GET', 'POST'])]
    public function newJson(Request $req, EvenementRepository $evenementRepository,NormalizerInterface $normaliser ): Response
    {
        $event = new Evenement();
        $entityManager = $this->getDoctrine()->getManager();
        $event->setNomEvent($req->get('Nom_event'));
        $event->setLocalisation($req->get('localisation'));
        $event->setImageEvent($req->get('image_event'));
        $event->setDateDebut((new \DateTime($req->get('date_debut'))));
        $event->setDateFin((new \DateTime($req->get('date_fin'))));
      //  $event->setCategorie($req->get('categorie'));
        

        
        $entityManager->persist($event);
        $entityManager->flush();
        $donNormaliser = $normaliser->normalize($event, "json", ['groups' => "evenements"]);
        return new Response(json_encode($donNormaliser));


    }

    #[Route('/events/{id}', name: 'app_don_ShowEvent', methods: ['GET'])]
    public function showEvent($id, EvenementRepository $donRepository, NormalizerInterface $normaliser): Response
    {
        $don = $donRepository->find($id);
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "evenements"]);
        $json = json_encode($donNormaliser);
        return new Response($json);
    }
   /* #[Route('/user/{id}', name: 'app_don_ShowEvent', methods: ['GET'])]
    public function showUser($id, UserRepository $userRepository, NormalizerInterface $normaliser): Response
    {
        $don = $userRepository->find($id);
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "user"]);
        $json = json_encode($donNormaliser);
        return new Response($json);
    }*/

    #[Route('/', name: 'app_don_ShowDon', methods: ['GET'])]
    public function showAll( EvenementRepository $donRepository, NormalizerInterface $normaliser): Response
    {
        $don = $donRepository->findAll();
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "evenements"]);
        $json = json_encode($donNormaliser);
        return new Response($json);
    }

    #[Route('/deletJson/{id}', name: 'json_delet')]
    public function deletjson($id,NormalizerInterface $normalizer,Request $request,ManagerRegistry $doctrine,EvenementRepository $repository2)
    {
        $em=$doctrine->getManager();
        $activite=$repository2->find($id);
        $em->remove($activite);
        $em->flush();


        $jsonContent=$normalizer->normalize($activite,'json',['groups'=>"evenements"]);
        $json=json_encode($jsonContent);
        return new Response("Activite Deleted Successfuly".$json);
    }

    #[Route('/participer/{ide}/{idu}', name: 'json_participer')]
    public function participate($ide,$idu, NormalizerInterface $normalizer)
    {
        $entityManager = $this->getDoctrine()->getManager();
    $event = $entityManager->getRepository(Event::class)->find($ide);
    $user = $entityManager->getRepository(User::class)->find(2);
 
    $event->addUser($user);
    $event->setNbParticipants($event->getNbParticipants()+1);
    
    $entityManager->persist($event);
    $entityManager->flush();
    
    $eventData = $normalizer->normalize($event, null, ['groups' => 'event:read']);
    $jsonData = json_encode($eventData);
    return new Response($jsonData, 200, ['Content-Type' => 'application/json']);
    }


    #[Route('/modifjson/{id}', name: 'app_don_ShowDon', methods: ['GET'])]
    public function ModifDon(Request $req, $id, Evenement $donRepository, NormalizerInterface $normaliser): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $don = $entityManager->getRepository(Evenement::class)->find($id);
        $don->setNomEvent($req->get('Nom_event'));
        $don->setLocalisation($req->get('localisation'));
        $don->setImageEvent($req->get('image_event'));
        //$don->setDateDebut($req->get('date_debut'));
        //$don->setDateFin($req->get('date_fin'));
       
       // $don->setCategorie($req->get('categorie'));
       
        
        $entityManager->persist($don);
        $entityManager->flush();
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "evenements"]);
        return new Response(json_encode($donNormaliser));
    }
 
    #[Route('/AllEvent', name: 'app_evenement_indexAll', methods: ['GET'])]
    public function indexAll(EvenementRepository $repo, NormalizerInterface $normaliser): Response
    {
        $don = $repo->findAll();
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "evenements"]);
        $json = json_encode($donNormaliser);
        return new Response($json);
    }





    

}
