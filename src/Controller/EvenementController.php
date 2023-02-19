<?php

namespace App\Controller;

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

#[Route('/evenement')]
class EvenementController extends AbstractController
{
  
     #[Route('/{id}/addParticipation', name: 'addParticipation', methods: ['GET'])]
    public function addParticipation(Request $request,EvenementRepository $eventRepository,$id, UserRepository $rep2,SessionInterface $session
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

        $this->addFlash('success', 'You have successfully participated in this event.');

        return $this->redirectToRoute('app_evenement_index');
       
        
    } 


    #[Route('/{id}/cancelP', name: 'cancelP', methods: ['GET'])]
    public function cancelP(Request $request,EvenementRepository $eventRepository,$id, UserRepository $rep2,SessionInterface $session
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
    
            $this->addFlash('success', 'You have successfully cancelled your participation in this event.');
    
            return $this->redirectToRoute('app_evenement_index');
        
    } 





    #[Route('/search', name: 'app_evenement_recherche')]
    public function index2(EvenementRepository $evenementRepository , Request $request): Response
    {

        $evenements = $evenementRepository->findAll();
        $form=$this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
                      
            $result = $evenementRepository->findByNom($form->getData());
            return $this->render(
                'evenement/resultatRech.html.twig', array(
                   
                    "resultOfSearch" => $result,
                    "formSearch" => $form->createView() 
                    ));
        }
        return $this->render('evenement/search.html.twig', [
            "evenements" => $evenements,
            "formSearch" => $form->createView() 
             
        ]);
    }

    #[Route('/', name: 'app_evenement_index' , methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository , Request $request, PaginatorInterface $paginator,SessionInterface $session,UserRepository $rep2): Response
    {
        $evenements=$evenementRepository->findAll();
        $user= $rep2->find(2);
       
        


       /*  $evenements = $paginator->paginate(
            $evenements, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
 */
        


        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'user' => $user,
        ]);
    }
   




   

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
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
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
    



}
