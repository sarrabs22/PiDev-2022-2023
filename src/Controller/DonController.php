<?php

namespace App\Controller;

use App\Entity\Don;
use App\Form\DonType;
use App\Form\SearchDonType;
use App\Repository\DonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/don')]
class DonController extends AbstractController
{
    #[Route('/', name: 'app_don_index', methods: ['GET'])]
    public function index(DonRepository $donRepository): Response
    {
        return $this->render('don/index.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);
    }
    #[Route('/client', name: 'app_don_Client', methods: ['GET'])]
    public function client(DonRepository $donRepository): Response
    {
        return $this->render('don/client.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);
    }
    #[Route('/Admin', name: 'app_don_Admin', methods: ['GET'])]
    public function Admin(DonRepository $donRepository): Response
    {
        return $this->render('don/donA.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_don_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DonRepository $donRepository): Response
    {
        $don = new Don();
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['Image']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFile = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFile
            );
            $don->setImage($newFile);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($don);
            $entityManager->flush();
            $donRepository->save($don, true);

            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/new.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }
    #[Route('/search', name: 'app_don_search')]
    public function listDonWithSearch(Request $request, DonRepository $DonRepository)
    {
        //All of Student
        $Don = $DonRepository->findAll();
        //search
        $searchForm = $this->createForm(SearchDonType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            if ($Nom_Don = $searchForm['NameD']->getData()) {
                $resulta = $DonRepository->searchNom($Nom_Don);
                return $this->render('don/searchDon.html.twig', array(
                    "dons" => $resulta,
                    "searchDon" => $searchForm->createView()
                ));
            } else if ($Localisation_Don = $searchForm['Localisation']->getData()) {
                $resulta1 = $DonRepository->searchLocalisation($Localisation_Don);
                return $this->render('don/searchDon.html.twig', array(
                    "dons" => $resulta1,
                    "searchDon" => $searchForm->createView()
                ));
            }
        }
        return $this->render('don/searchDon.html.twig', array(
            "dons" => $Don,
            "searchDon" => $searchForm->createView()
        ));
    }
    #[Route('/map', name: 'show_map')]
    public function map()
    {

        /* if ($id == $donRepository->getId()) {
            $address = $donRepository->getLocalisation();
        } */
        return $this->render('don/map.html.twig', []);
    }

    #[Route('/{id}', name: 'app_don_show', methods: ['GET'])]
    public function show(Don $don): Response
    {
        return $this->render('don/show.html.twig', [
            'don' => $don,
        ]);
    }
    #[Route('/client/{id}', name: 'app_don_showclient', methods: ['GET'])]
    public function show2(Don $don): Response
    {
        return $this->render('don/showClient.html.twig', [
            'don' => $don,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Don $don, DonRepository $donRepository): Response
    {

        $form = $this->createForm(DonType::class, $don);
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
            $don->setImage($newFilename);

            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/edit.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_don_delete', methods: ['POST'])]
    public function delete(Request $request, Don $don, DonRepository $donRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $don->getId(), $request->request->get('_token'))) {
            $donRepository->remove($don, true);
        }

        return $this->redirectToRoute('app_don_delete', [], Response::HTTP_SEE_OTHER);
    }
}
