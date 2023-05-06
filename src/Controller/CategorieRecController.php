<?php

namespace App\Controller;

use App\Entity\CategorieRec;
use App\Form\CategorieRecType;
use App\Repository\CategorieRecRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/categorieRec')]
class CategorieRecController extends AbstractController
{
    #[Route('/', name: 'app_categorie_rec_index', methods: ['GET'])]
    public function index(CategorieRecRepository $categorieRecRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $categorie_recs = $categorieRecRepository->findAll();
        $categorie_recs = $paginator->paginate(
            $categorie_recs, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('categorie_rec/index.html.twig', [
            'categorie_recs'=> $categorie_recs,
        ]);
    }

    #[Route('/new', name: 'app_categorie_rec_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieRecRepository $categorieRecRepository, EntityManagerInterface $em): Response
    {
        $erreur=0;
        $categorieRec = new CategorieRec();
        $form = $this->createForm(CategorieRecType::class, $categorieRec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Nom = $form->get('Nom')->getData();
            $categorie = $em
                ->getRepository(CategorieRec::class)
                ->findOneBy(['Nom' => $Nom]);
            if ($categorie!== null) {
                $erreur = 1;
            } else {
            $categorieRecRepository->save($categorieRec, true);
          
            return $this->redirectToRoute('app_categorie_rec_index', [], Response::HTTP_SEE_OTHER);
        }
    }

        return $this->renderForm('categorie_rec/new.html.twig', [
            'categorie_rec' => $categorieRec,
            'form' => $form,
            'erreur' => $erreur,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_rec_show', methods: ['GET'])]
    public function show(CategorieRec $categorieRec): Response
    {
        return $this->render('categorie_rec/show.html.twig', [
            'categorie_rec' => $categorieRec,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_rec_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieRec $categorieRec, CategorieRecRepository $categorieRecRepository): Response
    {
        $form = $this->createForm(CategorieRecType::class, $categorieRec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRecRepository->save($categorieRec, true);

            return $this->redirectToRoute('app_categorie_rec_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_rec/edit.html.twig', [
            'categorie_rec' => $categorieRec,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_rec_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieRec $categorieRec, CategorieRecRepository $categorieRecRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieRec->getId(), $request->request->get('_token'))) {
            $categorieRecRepository->remove($categorieRec, true);
        }

        return $this->redirectToRoute('app_categorie_rec_index', [], Response::HTTP_SEE_OTHER);
    }
}
