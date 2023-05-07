<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Comments;
use App\Form\ReclamationType;
use App\Form\CommentsType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\File\File;
use App\Form\SearchType;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twilio\Rest\Client;



#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
      #[Route('/search', name: 'app_reclamation_recherche')]
    public function index2(ReclamationRepository $repo , Request $request): Response
    {
       
        $rec = $repo->findAll();
        $form=$this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
                      
            $result = $repo->findByNom($form->getData());
            return $this->render(
                'reclamation/resultatRech.html.twig', array(
                   
                    "resultOfSearch" => $result,
                    
                    
                    ));
        }
        return $this->render('reclamation/search.html.twig', [
            
            "formSearch" => $form->createView() 
             
        ]);
    }
    
    //Exporter pdf (composer require dompdf/dompdf)
     /**
      * @Route("/pdf", name="PDF_Reclamation", methods={"GET"})
      */
     /* public function pdf(ReclamationRepository $ReclamationRepository)
      {
          // Configure Dompdf according to your needs
          $pdfOptions = new Options();
          $pdfOptions->set('defaultFont', 'Arial');
  
          // Instantiate Dompdf with our options
          $dompdf = new Dompdf($pdfOptions);
          // Retrieve the HTML generated in our twig file
          $html = $this->renderView('reclamation/pdf1.html.twig', [
              'reclamations' => $ReclamationRepository->findAll(),
          ]);
  
          // Load HTML to Dompdf
          $dompdf->loadHtml($html);
          // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
          $dompdf->setPaper('A4', 'portrait');
  
          // Render the HTML as PDF
          $dompdf->render();
          // Output the generated PDF to Browser (inline view)
          $dompdf->stream("ListeDesreclmations.pdf", [
              "reclamations" => true
          ]);
          
      }*/

      #[Route('/pdf/{id}', name: 'app_reclamation_pdf')]
    public function downloadPdf($id): Response
    {
        $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        return $this->render('reclamation/pdf.html.twig', [
            'reclamation' => $reclamations,
        ]);
    }




    
    #[Route('/', name: 'app_reclamation_index')]
    public function index(ReclamationRepository $reclamationRepository, Request $request,  PaginatorInterface $paginator): Response
    {
        $rec= $reclamationRepository->findAll();
       

        
        $rec = $paginator->paginate(
            $rec, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        
        return $this->render('reclamation/index.html.twig', [
           
            'reclamations' => $rec,
            
           
        ]);
    }
    #[Route('/admin', name: 'app_reclamation_index_admin', methods: ['GET'])]
    public function index_admin(ReclamationRepository $reclamationRepository): Response
    {   
        

        return $this->render('reclamation/admin.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    
    #[Route('/choix', name: 'app_reclamation_choix', methods: ['GET'])]
    public function choix(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/choix.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/searchh', name: 'app_reclamation_index_searchh', methods: ['GET'])]
    public function search(ReclamationRepository $reclamationRepository): Response
    {   
        

        return $this->render('reclamation/search2.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/Chart', name: 'app_reclamation_chart', methods: ['GET'])]
    public function chartAction(EntityManagerInterface $em): Response
    {
        $query = $em->createQuery('SELECT c.MotifDeReclamation ,COUNT(c.id) as donation_count 
        FROM App\Entity\Reclamation c
        GROUP BY c.id
        ');
        $results = $query->getResult();
        $data = [];
        foreach ($results as $row) {
            $data[$row['MotifDeReclamation']] = $row['donation_count'];
        }
        return $this->render('reclamation/stat.html.twig', ['data' => $data]);
    }

    #[Route("/Allreclamation", name: 'list')]
    public function getreclamation(ReclamationRepository $repo , NormalizerInterface $normalizer)
    {
        $reclamation = $repo->findAll();
        $reclamationNormalises = $normalizer->normalize($reclamation,'json',['groups'=>"reclamation"]);

        $json = json_encode($reclamationNormalises);
        return new Response($json);

    }

    #[Route("/addReclamationJSON", name: 'addReclamationJson')]
    public function addReclamation(Request $req, NormalizerInterface $normalizer)
    {
        $em= $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $reclamation->setEtat($req->get('etat'));
        //$reclamation->setcontenu($req->get('contenu'));
        //$reclamation->setdatareclamation($req->get('data_reclamation'));
        $reclamation->setMotifDeReclamation($req->get('MotifDeReclamation'));
        $reclamation->setNumTelephone($req->get('NumTelephone'));
        $reclamation->setImage($req->get('Image'));
        $reclamation->setEmail ($req->get('Email'));
        $em->persist($reclamation);
        $em->flush();
    
        $jsonContent = $normalizer->normalize($reclamation,'json',['groups'=>"reclamation"]);
        return new Response(json_encode($jsonContent));

    }
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository, PersistenceManagerRegistry $doctrine,UserRepository $repouser): Response
    {
      $user = $repouser->find($this->getUser()->getUserIdentifier());
        $reclamation = new Reclamation();
        $DateActuelle = new \DateTime('NOW');
        $formattedDate = $DateActuelle->format('Y-m-d H:i:s');
        $reclamation->setDataReclamation($formattedDate);
        $reclamation->setEtat('en cours');
        $reclamation->setUser($user);
        $reclamation->setEmail($user->getEmail());
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
         {  
            $entityManager = $doctrine->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();
                  // Send SMS notification to admin
       /* $accountSid = 'AC9915eed7fbb7f651dc65d44a18a7eca4';
        $authToken = 'b37aef6701292755e6ac39fbed963e8d';
        $client = new Client($accountSid, $authToken);
        $message = $client->messages->create(
            '+21622552903', // replace with admin's phone number
            [
                'from' => '+15674092876', // replace with your Twilio phone number
                'body' => 'Une réclamation a bien été envoyée !' // replace with your message
            ]
        );*/
            $uploadedFile = $form['Image']->getData();
            $destination = 'C:\xampp\htdocs\public';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFile = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFile
            );
            $reclamation->setImage($newFile);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route("/reclamation/{id}", name: 'reclamation')]
    public function reclamationId($id, NormalizerInterface $normalizer , ReclamationRepository $repo)
    {
        $reclamation = $repo->find($id);
        
        $reclamationNormalises = $normalizer->normalize($reclamation, 'json', ['groups' =>"reclamation"]);
        return new Response(json_encode($reclamationNormalises));
    }
        
    
    #[Route("/UpdateReclamationJSON/{id}", name: 'UpdateReclamationJson')]
    public function UpdateReclamation(Request $req,$id,NormalizerInterface $normalizer)
    {

        $em=$this->getDoctrine()->getManager();
        $reclamation= $em->getRepository(Reclamation::class)->find($id);
        
        //$reclamation->setcontenu($req->get('contenu'));
        //$reclamation->setdatareclamation($req->get('data_reclamation'));
        $reclamation->setEtat($req->get('etat'));
        $reclamation->setMotifDeReclamation($req->get('MotifDeReclamation'));
        $reclamation->setNumTelephone($req->get('NumTelephone'));
        $reclamation->setImage($req->get('Image'));
        $reclamation->setEmail ($req->get('Email'));
        $em->flush();
        $jsonContent = $normalizer->normalize($reclamation,'json',['groups'=>"reclamation"]);
        return new Response("reclamation updated successfully" .json_encode($jsonContent));

    }
    #[Route("/deleteReclamationJSON/{id}", name: 'deleteReclamationJson')]
    public function deleteReclamation(Request $req,$id,NormalizerInterface $normalizer)
    {
        $em =$this->getDoctrine()->getManager();
        $reclamation= $em->getRepository(Reclamation::class)->find($id);
        $em->remove($reclamation);
        $em->flush();
        $jsonContent = $normalizer->normalize($reclamation,'json',['groups'=>"reclamation"]);
        return new Response("reclamation deleted successfully" .json_encode($jsonContent));

    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository, PersistenceManagerRegistry $doctrine): Response
    { 
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {   

            $uploadedFile = $form['Image']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $reclamation->setImage($newFilename);

            $doctrine->getManager()->flush();
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index_admin', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete2(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail (ManagerRegistry $mg ,ReclamationRepository $X ,Request $request, FlashBagInterface $flashBag, $id): Response
    {    
    
    $repo=$mg->getRepository(Reclamation::class);
    $resultat = $repo ->find($id);
    

    //Partie commentaires 
        // On cree le commentaire "vierge"

        $comment = new Comments();
         
        // On génére le formulaire 
        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->handleRequest($request);

        //Traitement du formulaire
        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setCreatedAt(new DateTime());
            $comment->setReclamations($resultat);
            $resultat->addComment($comment);
  
            // On recupere le contenu du champ parentid
             $parentid = $commentForm->get("parentid")->getData();

            // On va chercher le commentaire correspondant 
            $em = $this->getDoctrine()->getManager();
            if($parentid != null)
            {
              $parent = $em->getRepository(Comments::class)->find($parentid);
            }
            // On définit le parent 
            $comment->setParent($parent ?? null);
            
            $em->persist($comment);
            $em->flush();


            $flashBag->add('success', 'Votre commentaire a bien été envoyé !');
            return $this->redirectToRoute('detail', ['id' => $id]);
    
        }

        return $this->render('reclamation/comment.html.twig', [
            'reclamation' => $resultat,
            'commentForm'=>$commentForm->createView()
        ]);
    }

    
    
}