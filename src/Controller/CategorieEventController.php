<?php

namespace App\Controller;

use App\Entity\CategorieEvent;
use App\Form\CategorieEventType;
use App\Repository\CategorieEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/categorieEvent')]
class CategorieEventController extends AbstractController
{
    #[Route('/', name: 'app_categorieEvent_index', methods: ['GET'])]
    public function index(CategorieEventRepository $categorieRepository): Response
    {
        return $this->render('categorieEvent/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorieEvent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieEventRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        $erreur=0;
        $categorie = new CategorieEvent();
        $form = $this->createForm(CategorieEventType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $NameCa = $form->get('nom_categ_event')->getData();
            $category = $em
                ->getRepository(CategorieEvent::class)
                ->findOneBy(['nom_categ_event' => $NameCa]);
            if ($category !== null) {
                $erreur = 1;
            } else {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorieEvent_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('categorieEvent/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
            'erreur' => $erreur,
        ]);
    }

    #[Route('/{id}', name: 'app_categorieEvent_show', methods: ['GET'])]
    public function show(CategorieEvent $categorie): Response
    {
        return $this->render('categorieEvent/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorieEvent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieEvent $categorie, CategorieEventRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieEventType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorieEvent/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorieEvent_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieEvent $categorie, CategorieEventRepository $categorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie, true);
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
