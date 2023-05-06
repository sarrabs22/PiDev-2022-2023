<?php

namespace App\Controller;

use App\Entity\CategorieAssociation;
use App\Form\CategorieAssociationType;
use App\Repository\CategorieAssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorieAssociation')]
class CategorieAssociationController extends AbstractController
{
    #[Route('/', name: 'app_categorieAssociation_index', methods: ['GET'])]
    public function index(CategorieAssociationRepository $categorieRepository): Response
    {
        return $this->render('categorieAssociation/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorieAssociation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieAssociationRepository $categorieRepository): Response
    {
        $categorie = new CategorieAssociation();
        $form = $this->createForm(CategorieAssociationType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorieAssociation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorieAssociation/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorieAssociation_show', methods: ['GET'])]
    public function show(CategorieAssociation $categorie): Response
    {
        return $this->render('categorieAssociation/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorieAssociation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieAssociation $categorie, CategorieAssociationRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieAssociationType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorieAssociation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorieAssociation/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorieAssociation_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieAssociation $categorie, CategorieAssociationRepository $categorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie, true);
        }

        return $this->redirectToRoute('app_categorieAssociation_index', [], Response::HTTP_SEE_OTHER);
    }
}
