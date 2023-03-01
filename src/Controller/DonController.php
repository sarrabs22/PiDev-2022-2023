<?php

namespace App\Controller;

use App\Entity\Don;
use App\Entity\GeocodingService;
use App\Form\DonType;
use App\Form\SearchDonType;
use App\Repository\DonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use willdurand\Geocoder\Provider\OpenStreetMap;
use willdurand\Geocoder\StatefulGeocoder;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

#[Route('/don')]
class DonController extends AbstractController
{
    #[Route('/', name: 'app_don_index', methods: ['GET'])]
    public function index(DonRepository $donRepository, Request $request, NormalizerInterface $normaliser): Response
    {


        return $this->render('don/index.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);
    }
    #[Route('/Alldons', name: 'app_don_indexAll', methods: ['GET'])]
    public function indexAll(DonRepository $donRepository, NormalizerInterface $normaliser): Response
    {
        $don = $donRepository->findAll();
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "dons"]);
        $json = json_encode($donNormaliser);
        return new Response($json);
    }


    #[Route('/client', name: 'app_don_Client', methods: ['GET'])]
    public function client(Request $request, DonRepository $donRepository, PaginatorInterface $paginator): Response
    {
        $don = $donRepository->findAll();
        return $this->render('don/client.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);

        $don = $paginator->paginate(
            $don, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
    }
    #[Route('/Admin', name: 'app_don_Admin', methods: ['GET'])]
    public function Admin(DonRepository $donRepository): Response
    {
        return $this->render('don/donA.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_don_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DonRepository $donRepository, NotifierInterface $notifier,): Response
    {
        $don = new Don();
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);
        $notifier->send(new Notification('Thank you for Adding a new Don.', ['browser']));
        if ($form->isSubmitted() && $form->isValid()) {
            $notifier->send(new Notification('Can you check your submission? There are some problems with it.', ['browser']));
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
            $this->addFlash('message', 'This don has been successfully added');
            $donRepository->save($don, true);
            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/new.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }
    #[Route('/adddonjson', name: 'app_don_newJson', methods: ['GET', 'POST'])]
    public function addDonJson(Request $req, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        $don = new Don();

        $don->setNameD($req->get('NameD'));
        $don->setQuantite($req->get('quantite'));
        $don->setDescription($req->get('Description'));
        $don->setLocalisation($req->get('Localisation'));
        $don->setCategoryD($req->get('categoryD'));
        $don->setImage($req->get('Image'));
        $don->setEmail($req->get('email'));
        $don->setNumero($req->get('Numero'));

        $entityManager->persist($don);
        $entityManager->flush();

        $json = $serializer->serialize($don, "json", ["groups" => "event:read"]);
        return new Response("Event added" . json_encode($json));
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


    #[Route('/dons/{id}', name: 'app_don_ShowDon', methods: ['GET'])]
    public function showDon($id, DonRepository $donRepository, NormalizerInterface $normaliser): Response
    {
        $don = $donRepository->find($id);
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "dons"]);
        $json = json_encode($donNormaliser);
        return new Response($json);
    }
    #[Route('/map2/{id}', name: 'show_map2')]
    public function map2($id, DonRepository $donRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $don = $entityManager->getRepository(Don::class)->find($id);


        return $this->render('don/test2.html.twig', [
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


    #[Route('/modifjson/{id}', name: 'app_don_ModifDon', methods: ['GET'])]
    public function ModifDon(Request $req, $id, DonRepository $donRepository, NormalizerInterface $normaliser): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $don = $entityManager->getRepository(Don::class)->find($id);
        $don->setNameD($req->get('NameD'));
        $don->setQuantite($req->get('quantite'));
        $don->setDescription($req->get('Description'));
        $don->setLocalisation($req->get('Localisation'));
        $don->setCategoryD($req->get('categoryD'));
        $don->setImage($req->get('Image'));
        $don->setEmail($req->get('email'));
        $don->setNumero($req->get('Numero'));
        $entityManager->persist($don);
        $entityManager->flush();
        $donNormaliser = $normaliser->normalize($don, "json", ['groups' => "dons"]);
        return new Response(json_encode($donNormaliser));
    }
    #[Route("/deleteJson/{id}", name: "app_don_DeleteDon")]
    public function deleteJson($id, SerializerInterface $serializer, DonRepository $donRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $don = $entityManager->getRepository(Don::class)->find($id);
        $donRepository->remove($don);
        $entityManager->flush();

        $json = $serializer->serialize($don, "json", ["groups" => "dons"]);
        return new Response("event deleted" . json_encode($json));
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
