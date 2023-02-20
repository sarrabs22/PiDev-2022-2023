<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityManagerInterface;
#use Doctrine\Common\Persistence\ObjectRepository;



#[Route('/annonce/crud')]
class AnnonceCrudController extends AbstractController
{
    #[Route('/', name: 'app_annonce_crud_index', methods: ['GET'])]
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('annonce_crud/index.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }
    #[Route('/admin', name: 'app_annonce_crud_admin', methods: ['GET'])]
    public function admin(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('annonce_crud/afficher.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }

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
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFile = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFile
            );
            $annonce->setImage($newFile);
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

    #[Route('/{id}', name: 'app_annonce_crud_show', methods: ['GET'])]
    public function show(Annonces $annonce): Response
    {
        return $this->render('annonce_crud/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

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

    #[Route('/{id}', name: 'app_annonce_crud_delete', methods: ['POST'])]
    // #[Route('/{id}', name: 'app_annonce_crud_delete', methods: ['DELETE'])]
    public function delete(Request $request, Annonces $annonce, AnnoncesRepository $annoncesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->request->get('_token'))) {
            $annoncesRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_crud_index', [], Response::HTTP_SEE_OTHER);
    }


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
