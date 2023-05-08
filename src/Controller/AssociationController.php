<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\CategorieAssociation;
use App\Form\AssociationType;
use App\Form\FilterAssociationsType;
use App\Repository\AssociationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Normalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twilio\Rest\Client;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;

#[Route('/association')]
class AssociationController extends AbstractController
{
    #[Route('/', name: 'app_association_index', methods: ['GET'])]
    public function index(AssociationRepository $associationRepository): Response
    {
        return $this->render('association/index.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }


    
    #[Route('/choix', name: 'app_association_choix', methods: ['GET'])]
    public function choix(AssociationRepository $associationRepository): Response
    {
        return $this->render('association/choix.html.twig', [
            'associations' => $associationRepository->findAll()
        ]);
    }



    #[Route('/filter', name: 'app_association_filter', methods: ['GET'])]
public function filterAssociations(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = $request->query->get('categorie');
        $categories = $this->getDoctrine()->getRepository(CategorieAssociation::class)->findAll();

        if ($categorie) {
            $associations = $this->getDoctrine()->getRepository(Association::class)->findBy([
                'categorie' => $categorie,
            ]);
        } else {
            $associations = $this->getDoctrine()->getRepository(Association::class)->findAll();
        }

        return $this->render('association/hmed.html.twig', [
            'associations' => $associations,
            'categories' => $categories,
        ]);
    }
    

    #[Route("/Allassociation", name: 'list_association')]
    public function getassociation(AssociationRepository $repo , NormalizerInterface $normalizer)
    {
        $association = $repo->findAll();
        $associationNormalises = $normalizer->normalize($association,'json',['groups'=>"association"]);

        $json = json_encode($associationNormalises);
        return new Response($json);

    }

    #[Route("/addAssociationJSON", name: 'addAssociationJson')]
    public function addAssociation(Request $req, NormalizerInterface $normalizer)
    {
        $em= $this->getDoctrine()->getManager();
        $association = new Association();
        $association->setnom($req->get('nom'));
        $association->setnumero($req->get('numero'));
        $association->setmail($req->get('mail'));
        $association->setadresse($req->get('adresse'));
        $association->setCodePostal($req->get('CodePostal'));
        $association->setville ($req->get('ville'));
        $association->setImage ($req->get('Image'));
        $em->persist($association);
        $em->flush();
        $jsonContent = $normalizer->normalize($association,'json',['groups'=>"association"]);
        return new Response(json_encode($jsonContent));

    }


   


    #[Route('/new', name: 'app_association_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AssociationRepository $associationRepository,UserRepository $userepo): Response
    {
        $association = new Association();

        $user= $userepo->find($this->getUser()->getUserIdentifier());
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);
        $association->setUser($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['Image']->getData();
            $destination = 'C:\xampp\htdocs\public';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFile = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFile
            );
            $association->setImage($newFile);
            $entityManager = $this->getDoctrine()->getManager();
            $association->setUser($user);
            $entityManager->persist($association);
            $entityManager->flush();
            $associationRepository->save($association, true);

       /*  //    code ell sms 
            $accountSid = 'AC7baee01e459dc347a9e9f0a9b8f744c5';
            $authToken = '9c4d3175f524029937ea448f92ae988e';
            $client = new Client($accountSid, $authToken);
            $message = $client->messages->create(
                '+21698773438', // replace with admin's phone number
                [
                    'from' => '++12764098996', // replace with your Twilio phone number
                    'body' => 'une nouvelle association est inscrit nommé: '.$form->get('nom')->getData(), // replace with your message
                ]
            ); */


            return $this->redirectToRoute('app_association_choix', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association/new.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_association_show', methods: ['GET'])]
    public function show(Association $association): Response
    {
        return $this->render('association/show.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/show2/{id}', name: 'show2', methods: ['GET'])]
    public function show2(Association $association): Response
    {
        return $this->render('association/show2.html.twig', [
            'association' => $association,
        ]);
    }


    #[Route("/association/{id}", name: 'association')]
    public function associationId($id, NormalizerInterface $normalizer , AssociationRepository $repo)
    {
        $association = $repo->find($id);
        
        $associationNormalises = $normalizer->normalize($association, 'json', ['groups' =>"association"]);
        return new Response(json_encode($associationNormalises));
    }
        
    
    #[Route("/UpdateAssociationJSON/{id}", name: 'UpdateAssociationJson')]
    public function UpdateAssociation(Request $req,$id,NormalizerInterface $normalizer)
    {

        $em=$this->getDoctrine()->getManager();
        $association= $em->getRepository(Association::class)->find($id);
        
        $association->setnom($req->get('nom'));
        $association->setnumero($req->get('numero'));
        $association->setmail($req->get('mail'));
        $association->setadresse($req->get('adresse'));
        $association->setCodePostal($req->get('CodePostal'));
        $association->setville ($req->get('ville'));
        $association->setImage ($req->get('Image'));
        $em->flush();
        $jsonContent = $normalizer->normalize($association,'json',['groups'=>"association"]);
        return new Response("association updated successfully" .json_encode($jsonContent));

    }
    #[Route("/deleteAssociationJSON/{id}", name: 'deleteAssociationJson')]
    public function deleteAssociation(Request $req,$id,NormalizerInterface $normalizer)
    {
        $em =$this->getDoctrine()->getManager();
        $association= $em->getRepository(Association::class)->find($id);
        $em->remove($association);
        $em->flush();
        $jsonContent = $normalizer->normalize($association,'json',['groups'=>"association"]);
        return new Response("association deleted successfully" .json_encode($jsonContent));

    }



    #[Route('/{id}/edit', name: 'app_association_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Association $association, AssociationRepository $associationRepository): Response
    {

        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

/** @var UploadedFile $uploadedFile */
$uploadedFile = $form['Image']->getData();
$destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
$originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
$newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
$uploadedFile->move(
    $destination,
    $newFilename
);
$association->setImage($newFilename);

$this->getDoctrine()->getManager()->flush();

            $associationRepository->save($association, true);

            return $this->redirectToRoute('app_association_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association/edit.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_association_delete', methods: ['POST'])]
    public function delete(Request $request, Association $association, AssociationRepository $associationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$association->getId(), $request->request->get('_token'))) {
            $associationRepository->remove($association, true);
        }

        return $this->redirectToRoute('app_association_index', [], Response::HTTP_SEE_OTHER);
    }

    



     






}
