<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Commentaires;
use App\Form\AnnoncesType;
use App\Form\CommentairesType;
use  App\Form\SearchType;
use App\Repository\AnnoncesRepository;
use App\Repository\CommentairesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
#use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function PHPUnit\Framework\stringContains;


// ************************** recherche **************************
#[Route('/annonce/crud')]
class AnnonceCrudController extends AbstractController
{
    #[Route("/Allannonce", name: 'list_annonces')]
    public function getannone(AnnoncesRepository $repo, NormalizerInterface $normalizer)
    {
        $annonce = $repo->findAll();
        $annonceNormalises = $normalizer->normalize($annonce, 'json', ['groups' => "annonce/crud"]);

        $json = json_encode($annonceNormalises);
        return new Response($json);
    }


    #[Route('/search', name: 'app_annonce_recherche')]
    public function index2(AnnoncesRepository $repo, Request $request): Response
    {

        $rec = $repo->findAll();
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $result = $repo->findByNom($form->getData());
            return $this->render(
                'annonce_crud/resultatRech.html.twig',
                array(

                    "resultOfSearch" => $result,


                )
            );
        }
        return $this->render('annonce_crud/search.html.twig', [

            "formSearch" => $form->createView()

        ]);
    }

    // **************************liste des annonces page d'accueil **************************

    #[Route('/', name: 'app_annonce_crud_index', methods: ['GET'])]
    public function index(AnnoncesRepository $annoncesRepository): Response
    {


        return $this->render('annonce_crud/index.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }


    // ******************affichage de la liste des annonces (back) **************************

    #[Route('/affichback', name: 'affichback', methods: ['GET'])]
    public function indexback(AnnoncesRepository $annoncesRepository): Response
    {


        return $this->render('annonce_crud/affichback.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }


    // ************************** liste des annonces **************************

    #[Route('/admin', name: 'app_annonce_crud_admin', methods: ['GET'])]
    public function admin(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('annonce_crud/afficher.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }

    /* #[Route('/affich', name: 'app_affich', methods: ['GET'])]
    public function app_affich(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('annonce_crud/affichage1.html.twig', ['annonces' => $annoncesRepository->findAll(),]);
    } */


    // **************************mobile **************************
   


    #[Route("/addAnnonceJSON", name: 'addAnnonceJson')]
    public function addannonce(Request $req, NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $annonce = new Annonces();
        $annonce->setDescription($req->get('Description'));
        $annonce->setImage($req->get('Image'));
        //$annonce->setDatePublication((new \DateTime($req->get('DatePublication'))));
        $annonce->setadresse($req->get('adresse'));
        $em->persist($annonce);
        $em->flush();
        $jsonContent = $normalizer->normalize($annonce, 'json', ['groups' => "annonce/crud"]);
        return new Response(json_encode($jsonContent));
    }



    // ************************** Ajout **************************

    #[Route('/new', name: 'app_annonce_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnoncesRepository $annoncesRepository, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonces();
        $DateActuelle = new \DateTime('NOW');
        $formattedDate = $DateActuelle->format('Y-m-d H:i:s');
        $annonce->setDatePublication($formattedDate);
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['Image']->getData();
            $destination = 'C:\xampp\htdocs\public';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFile = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFile
            );
            $annonce->setImage($newFile);
            $annonce->setNombreEtoiles(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();
            $annoncesRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_crud_index', [], Response::HTTP_SEE_OTHER);
        }



        return $this->renderForm('annonce_crud/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    // ************************** afficher une annonce (html) **************************

    #[Route('/{id}', name: 'app_annonce_crud_show', methods: ['GET'])]
    public function show(Annonces $annonce): Response
    {
        return $this->render('annonce_crud/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }


    // ************************** afficher une annonce (Json) **************************

    #[Route("/annonce/{id}", name: 'annonce')]
    public function annonceId($id, NormalizerInterface $normalizer, AnnoncesRepository $repo)
    {
        $annonce = $repo->find($id);

        $annonceNormalises = $normalizer->normalize($annonce, 'json', ['groups' => "annonce/crud"]);
        return new Response(json_encode($annonceNormalises));
    }

    // ************************** update une annonce (Json) **************************

    #[Route("/UpdateAnnonceJSON/{id}", name: 'UpdateAnnonceJson_annonce_mobile',methods :['GET','POST'])]
    public function Updateannonce(Request $req, $id, NormalizerInterface $normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository(Annonces::class)->find($id);

        $annonce->setDescription($req->get('Description'));
        $annonce->setImage($req->get('Image'));
       // $annonce->setDatePublication($req->get('DatePublication'));
        $annonce->setadresse($req->get('adresse'));

        $em->flush();
        $jsonContent = $normalizer->normalize($annonce, 'json', ['groups' => "annonce/crud"]);
        return new Response("Annonce updated successfully" . json_encode($jsonContent));
    }
    #[Route("/deleteannonceJSON/{id}", name: 'deleteannonceJson')]
    public function deleteannonce(Request $req, $id, NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository(Annonces::class)->find($id);
        $em->remove($annonce);
        $em->flush();
        $jsonContent = $normalizer->normalize($annonce, 'json', ['groups' => "annonce"]);
        return new Response("annonce deleted successfully" . json_encode($jsonContent));
    }

    // ************************** supprimer reponse **************************

    #[Route('/delete/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    // #[Route('/{id}', name: 'app_annonce_crud_delete', methods: ['DELETE'])]
    public function deleteCommentaire(Request $request, Commentaires $commentaires, CommentairesRepository $commentairesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaires->getId(), $request->request->get('_token'))) {
            $commentairesRepository->remove($commentaires, true);
        }

        return $this->redirectToRoute('app_annonce_crud_index', [], Response::HTTP_SEE_OTHER);
    }

    // ************************** modifier une annonce **************************

    #[Route('/{id}/edit', name: 'app_annonce_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonces $annonce, AnnoncesRepository $annoncesRepository): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annoncesRepository->save($annonce, true);
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['Image']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $annonce->setImage($newFilename);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_annonce_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_crud/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }



    // ************************** supprimer une annonce **************************

    #[Route('/{id}', name: 'app_annonce_crud_delete', methods: ['POST'])]
    // #[Route('/{id}', name: 'app_annonce_crud_delete', methods: ['DELETE'])]
    public function delete(Request $request, Annonces $annonce, AnnoncesRepository $annoncesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->request->get('_token'))) {
            $annoncesRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_crud_index', [], Response::HTTP_SEE_OTHER);
    }






    // ************************** afficher map **************************

    #[Route('/map2/{id}', name: 'show_map2')]
    public function map2($id, AnnoncesRepository $annoncesRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $entityManager->getRepository(Annonces::class)->find($id);


        return $this->render('annonce_crud/test2.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    // ************************** commentaire **************************

    #[Route('/detail/{id}', name: 'detail')]
    public function detail(ManagerRegistry $mg, AnnoncesRepository $X, Request $request, $id): Response
    {

        $repo = $mg->getRepository(Annonces::class);
        $resultat = $repo->find($id);


        //Partie commentaires 
        // On crée le commentaire "vierge"

        $commentaires = new Commentaires();

        // On génére le formulaire 
        $commentairesForm = $this->createForm(CommentairesType::class, $commentaires);
        $commentairesForm->handleRequest($request);

        //Traitement du formulaire
        if ($commentairesForm->isSubmitted() && $commentairesForm->isValid()) {
            $commentaires->setDateCreation(new DateTime());
            $commentaires->setannonces($resultat);
            $resultat->addCommentaire($commentaires);


            // On recupere le contenu du champ parentid
            $parentid = $commentairesForm->get("parentid")->getData();

            // On va chercher le commentaire correspondant 
            $em = $this->getDoctrine()->getManager();
            if ($parentid != null) {
                $parent = $em->getRepository(Commentaires::class)->find($parentid);
            }
            // On définit le parent 
            $commentaires->setParent($parent ?? null);
            $commentaires->setUser($this->getUser());
            $em->persist($commentaires);
            $em->flush();

            return $this->redirectToRoute('detail', ['id' => $id]);
        }

        return $this->render('annonce_crud/exem.html.twig', [
            'annonces' => $resultat,
            'commentairesForm' => $commentairesForm->createView()
        ]);





        /*
     * @Route("/api/objects/{id}", name="api_objects_delete", methods={"DELETE"})
    public function deleteObject($id, ObjectRepository $objectRepository, EntityManagerInterface $entityManager)
    {
        $object = $objectRepository->find($id);

        if (!$object) {
            throw $this->createNotFoundException('Cet objet n\'existe pas.');
        }

        $entityManager->remove($object);
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'L\'objet a été supprimé avec succès.',
        ]);
    } */
    }
}
