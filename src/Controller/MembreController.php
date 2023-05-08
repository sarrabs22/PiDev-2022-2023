<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\Categorie;
use App\Entity\Membre;
use App\Form\MembreType;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AssociationRepository;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/membre')]
class MembreController extends AbstractController
{
    #[Route('/', name: 'app_membre_index', methods: ['GET'])]
    public function index(MembreRepository $membreRepository): Response
    {
        return $this->render('membre/index.html.twig', [
            'membres' => $membreRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_membre_new', methods: ['GET', 'POST'])]
    public function new($id,Request $request,UserRepository $UserRepo, MembreRepository $membreRepository,AssociationRepository $associationRepository,CategorieRepository $categorieRepository, EntityManagerInterface $entityManager): Response
    {
        $membre = new Membre();
        $associationChoisi = new Association();
        $associationChoisi= $associationRepository->find($id);
        $user = $UserRepo->find($this->getUser()->getUserIdentifier());
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

            $data[]= $form->getData();
            if ($data && isset($data['yesExperience']) && !$data['yesExperience']) {
                $form->remove('experience');
            }
           
                    //associations lié a l 'utilistateur
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
            
          //  dd($associationIds);
            //Check if user connected to association
            $qb = $entityManager->createQueryBuilder();
            $qb->select('a.id')
               ->from('App\Entity\Association', 'a')
               ->join('a.Membres', 'm')
               ->where('m.User = :user')
               ->setParameter('user', $user);
                        
            $result = $qb->getQuery()->getResult();
            
            if (in_array($associationChoisi->getId(), $associationIds)) {
                // User has already participated in an association
                return $this->render('/association/retour.html.twig');
               //dd( $associationIds);
            } else {
                // User has not yet participated in any association
              //  return $this->render('no_participation.html.twig');
            }
            
            


            // $associationIds now contains an array of association IDs associated with the user
        if ($form->isSubmitted() && $form->isValid()) {
           
            
            $membre->addAssociation($associationChoisi);
            $membre->setUser($user);
            $membre->setNom($user->getNom());
            $membre->setPrenom($user->getPrenom());
            $membre->setMail($user->getEmail());
            $membreRepository->save($membre, true);
           
            
            
            return $this->redirectToRoute('app_membre_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $images = [];

        if ($request->isMethod('POST')) {
            $categoryId = $request->request->get('Categorie');
            $category = $entityManager->getRepository(Categorie::class)->find($categoryId);

            $query = $entityManager->createQuery(
                'SELECT a.Image FROM App\Entity\Association a
                JOIN a.categorie c
                WHERE c.id = :categoryId'
            )->setParameter('categoryId', $categoryId);

            $images = $query->getResult();
        }

        // $categorie = $categorieRepository->findAll();

        return $this->renderForm('membre/new.html.twig', [
            'membre' => $membre,
            'form' => $form,
            'associations' => $associationRepository->findAll(),
            'categorie'=>$categorieRepository->findAll(),
            'images'=>$images,
        ]);
    }

    #[Route('/{id}', name: 'app_membre_show', methods: ['GET'])]
    public function show(Membre $membre): Response
    {
        return $this->render('membre/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_membre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Membre $membre, MembreRepository $membreRepository): Response
    {
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membreRepository->save($membre, true);

            return $this->redirectToRoute('app_membre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('membre/edit.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membre_delete', methods: ['POST'])]
    public function delete(Request $request, Membre $membre, MembreRepository $membreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->request->get('_token'))) {
            $membreRepository->remove($membre, true);
        }

        return $this->redirectToRoute('app_membre_index', [], Response::HTTP_SEE_OTHER);
    }
}
