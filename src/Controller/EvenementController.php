<?php

namespace App\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Evenement;
use App\Entity\User;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\UserRepository;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;
#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/map2/{id}', name: 'show_map2')]
    public function map2($id, EvenementRepository $donRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $don = $entityManager->getRepository(Evenement::class)->find($id);


        return $this->render('evenement/test2.html.twig', [
            'evenement' => $don,
        ]);
    }
    
    
    #[Route('/triAsc', name: 'app_evenement_tri' , methods: ['GET'])]
    public function triAsc(EvenementRepository $evenementRepository  , Request $request, PaginatorInterface $paginator,SessionInterface $session,UserRepository $rep2): Response
    {
        $ns= $evenementRepository->getEventOrdredByName() ;
        $evenements=$evenementRepository->findAll();
        $user= $rep2->find(2);
      
        return $this->render('evenement/tri.html.twig', [
            'tri' => $ns,
            'user' => $user,
            'evenements' => $evenements,
           
        ]);


    }
    #[Route('/triDsc', name: 'app_evenement_triDsc' , methods: ['GET'])]
    public function triDsc(EvenementRepository $evenementRepository  , Request $request, PaginatorInterface $paginator,SessionInterface $session,UserRepository $rep2): Response
    {
        $ns= $evenementRepository->getEventOrdredByName2() ;
        $evenements=$evenementRepository->findAll();
        $user= $rep2->find(2);
        $evenements = $paginator->paginate(
            $evenements, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('evenement/triDsc.html.twig', [
            'triD' => $ns,
            'user' => $user,
            'evenements' => $evenements,
           
        ]);


    }
   /*  
   
    #[Route('/recherche_ajax', name: 'recherche_ajax' , methods: ['GET'])]

   public function rechercheAjax(Request $request,EvenementRepository $sr): JsonResponse
   {
       $requestString = $request->query->get('searchValue');
       dd($requestString)
       $resultats = $sr->findStudentByNsc($requestString);
    
       return $this->json($resultats);
   } */
    
   #[Route('/back', name: 'app_evenement_indexBack' , methods: ['GET'])]
   public function indexBack(EvenementRepository $evenementRepository   , PaginatorInterface $paginator, Request $request): Response
   {
    $evenements=$evenementRepository->findAll();

  /*   $evenements = $paginator->paginate(
        $evenements, // Requête contenant les données à paginer (ici nos articles)
        $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        3 // Nombre de résultats par page
    ); */
    return $this->render('evenement/indexback.html.twig', [
        'events' => $evenements,
        
        
    ]);



   }
    
    
    
   #[Route('/pdf/{id}', name: 'app_don_pdf')]
    public function downloadPdf($id,UserRepository $rep2): Response
    {
        $dons = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        return $this->render('evenement/pdf.html.twig', [
            'evenement' => $dons,
            
        ]);
    }
   
  
     #[Route('/{id}/addParticipation', name: 'addParticipation', methods: ['GET'])]
    public function addParticipation(Request $request , FlashyNotifier $flashy,EvenementRepository $eventRepository,$id, UserRepository $rep2,SessionInterface $session
    ): Response
    {
        $event=$eventRepository->find($id);
      
            $user= new User();
            $user= $rep2->find(2);
            $event->addUser($user);
            $event->setNbParticipants($event->getNbParticipants()+1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();

        // Send SMS notification to admin
        $accountSid = 'ACe78e17b02f6db96616cebcab40c3982a';
        $authToken = '74aacf7553901aa3402a7a382fe6797f';
        $client = new Client($accountSid, $authToken);
        $message = $client->messages->create(
            '+21626318708', // replace with admin's phone number
            [
                'from' => '+15674092850', // replace with your Twilio phone number
                'body' => $user->getName() . ' a participé à levenement ' . $event->getNomEvent() ,  // replace with your message
            ]
        );

        $flashy->success('Bienvenue !', 'http://your-awesome-link.com');

        return $this->redirectToRoute('app_evenement_index');
       
        
    } 




    #[Route('/{id}/cancelP', name: 'cancelP', methods: ['GET'])]
    public function cancelP(Request $request, FlashyNotifier $flashy,EvenementRepository $eventRepository,$id, UserRepository $rep2,SessionInterface $session
    ): Response
    {
        $event=$eventRepository->find($id);
      
            $user= new User();
            $user= $rep2->find(2);
            $event->RemoveUser($user);
            $event->setNbParticipants($event->getNbParticipants()-1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
             // Send SMS notification to admin
        $accountSid = 'ACe78e17b02f6db96616cebcab40c3982a';
        $authToken = '74aacf7553901aa3402a7a382fe6797f';
        $client = new Client($accountSid, $authToken);
        $message = $client->messages->create(
            '+21626318708', // replace with admin's phone number
            [
                'from' => '+15674092850', // replace with your Twilio phone number
                'body' => $user->getName() . ' a annulé ça participation à levenement ' . $event->getNomEvent() ,  // replace with your message
            ]
        );
    
            $flashy->success('Bye :(', 'http://your-awesome-link.com');
    
            return $this->redirectToRoute('app_evenement_index');
        
    } 





  /*   #[Route('/search', name: 'app_evenement_recherche')]
    public function index2(ReclamationRepository $repo , Request $request): Response
    {
       

        $rec = $repo->findAll();
        $form=$this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
                      
            $result = $evenementRepository->findByNom($form->getData());
            return $this->render(
                'evenement/resultatRech.html.twig', array(
                   
                    "resultOfSearch" => $result,
                    
                    
                    ));
        }
        return $this->render('evenement/index.html.twig', [
            
            "formSearch" => $form->createView() 
             
        ]);
    } */

    #[Route('/', name: 'app_evenement_index' , methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository  , Request $request, PaginatorInterface $paginator,SessionInterface $session,UserRepository $rep2): Response
    {
        $evenements=$evenementRepository->findAll();
        
        $user= $rep2->find(2);
        $form=$this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
                      
            $result = $evenementRepository->findByNom($form->getData());
            return $this->render(
                'evenement/resultatRech.html.twig', array(
                    "resultOfSearch" => $result,
                    'user' => $user,
                    ));
        }
        
         $evenements = $paginator->paginate(
            $evenements, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
 
        
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'user' => $user,
            "formSearch" => $form->createView() 
        ]);
    }

   
   
   
    /*  #[Route('/AllEvent', name: 'app_evenement_indexAll', methods: ['GET'])]
    public function indexAll(EvenementRepository $repo, NormalizerInterface $normaliser): Response
    {
        $don = $repo->findAll();
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "evenements"]);
        $json = json_encode($donNormaliser);
        return new Response($json);
    }


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

    #[Route('/modifjson/{id}', name: 'app_don_ShowDon', methods: ['GET'])]
    public function ModifDon(Request $req, $id, Evenement $donRepository, NormalizerInterface $normaliser): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $don = $entityManager->getRepository(Evenement::class)->find($id);
        $don->setNomEvent($req->get('Nom_event'));
        $don->setDateDebut($req->get('date_debut'));
        $don->setDateFin($req->get('date_fin'));
        $don->setLocalisation($req->get('localisation'));
        $don->setCategorie($req->get('categorie'));
        $don->setImageEvent($req->get('image_event'));
        
        $entityManager->persist($don);
        $entityManager->flush();
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "evenements"]);
        return new Response(json_encode($donNormaliser));
    }
 


    */

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository, FlashyNotifier $flashy ): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);
             /** @var UploadedFile $uploadedFile */
             $uploadedFile = $form['Image_Event']->getData();
             $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
             $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
             $newFile = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
             $uploadedFile->move(
                 $destination,
                 $newFile
                );
                $evenement->setImageEvent($newFile);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($evenement);
        $entityManager->flush();
        $flashy->success('Evenement crée!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement, UserRepository $rep2): Response
    {
        $user= $rep2->find(2);
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'user' =>$user
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['Image_Event']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $evenement->setImageEvent($newFilename);

            $this->getDoctrine()->getManager()->flush();
            

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('back/{id}', name: 'app_evenement_deleteBack', methods: ['POST'])]
    public function deleteback(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_indexBack', [], Response::HTTP_SEE_OTHER);
    }
    



}
