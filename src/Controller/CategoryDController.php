<?php

namespace App\Controller;

use App\Entity\CategoryD;
use App\Form\CategoryDType;
use App\Repository\CategoryDRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/category/d')]
class CategoryDController extends AbstractController
{
    #[Route('/', name: 'app_category_d_index', methods: ['GET'])]
    public function index(CategoryDRepository $categoryDRepository): Response
    {
        return $this->render('category_d/index.html.twig', [
            'category_ds' => $categoryDRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_category_d_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryDRepository $categoryDRepository, EntityManagerInterface $em): Response
    {
        $erreur = 0;
        $categoryD = new CategoryD();
        $form = $this->createForm(CategoryDType::class, $categoryD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $NameCa = $form->get('NameCa')->getData();
            $category = $em
                ->getRepository(CategoryD::class)
                ->findOneBy(['NameCa' => $NameCa]);
            if ($category !== null) {
                $erreur = 1;
            } else {
                $categoryDRepository->save($categoryD, true);

                return $this->redirectToRoute('app_category_d_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('category_d/new.html.twig', [
            'category_d' => $categoryD,
            'form' => $form,
            'erreur' => $erreur
        ]);
    }

    #[Route('/{id}', name: 'app_category_d_show', methods: ['GET'])]
    public function show(CategoryD $categoryD): Response
    {
        return $this->render('category_d/show.html.twig', [
            'category_d' => $categoryD,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_d_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryD $categoryD, CategoryDRepository $categoryDRepository): Response
    {
        $form = $this->createForm(CategoryDType::class, $categoryD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryDRepository->save($categoryD, true);

            return $this->redirectToRoute('app_category_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category_d/edit.html.twig', [
            'category_d' => $categoryD,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_d_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        CategoryD $categoryD,
        CategoryDRepository $categoryDRepository,

    ): Response {

        if ($this->isCsrfTokenValid('delete' . $categoryD->getId(), $request->request->get('_token'))) {
            $categoryDRepository->remove($categoryD, true);
        }

        return $this->redirectToRoute('app_category_d_index', [], Response::HTTP_SEE_OTHER);
    }
}
