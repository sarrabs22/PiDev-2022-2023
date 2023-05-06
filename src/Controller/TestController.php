<?php

namespace App\Controller;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\BotMan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
  
       
       // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        /* $profilePicture = $user->getImage(); */

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
           
        ]);
    }
    #[Route('/test/message', name: 'app_test_message', methods: ['POST','GET'])]
        public function handleMessage(Request $request)
        {
            $message = $request->request->get('message');
    
    // Initialize the response message to an empty string
    $responseMessage = '';
    
    // Check if the message contains the word "hello"
    if (stripos($message, 'salut') !== false) {
        $responseMessage = 'salut cv ?!';
    }
    
    // Check if the message contains the word "goodbye"
    if (stripos($message, 'goodbye') !== false) {
        $responseMessage = 'Goodbye! Have a nice day.';
    }

    if (stripos($message, 'site') !== false) {
        $responseMessage = 'Notre application web a pour but le developpement durable';
    }
    if (stripos($message, 'developpement durable') !== false || stripos($message, 'durable') !== false) {
        $responseMessage = "Le developpement durable est une conception du developpement qui s inscrit dans une perspective de long terme et en integrant les contraintes environnementales et sociales a l economie.";
    }
    if (stripos($message, 'reclamation') !== false) {
        $responseMessage = 'Vous pouvez saisir une reclamation a travers notre interface reclamation';
    }
    if (stripos($message, 'objectif') !== false) {
        $responseMessage = 'notre objectif est de rendre les ressources de notre planète plus durable';
    }
    if (stripos($message, 'don') !== false || stripos($message, 'donner') !== false) {
        $responseMessage = 'Vous pouvez donner un don a travers l interface don de l utilisateur si vous etes inscrits en tant que donneur';
    }
    if (stripos($message, 'don') !== false || stripos($message, 'recuperer') !== false) {
        $responseMessage = 'Vous pouvez recuperer un don a travers l interface de don si vous etes inscrits en tant que receveur';
    }
    if (stripos($message, 'aide') !== false ) {
        $responseMessage = 'Notre site vous offre la possiblite de recuperer ou donner des objets ,vous pouvez recuperer des objets ou donner des objets,il vous suffit simplement de saisir les informations de vos objets';
    }
    if (stripos($message, 'association') !== false ) {
        $responseMessage = 'Vous pouvez creez une association et cree des membres et faire des associations';
    }
    if (stripos($message, 'annonce') !== false ) {
        $responseMessage = 'Vous pouvez creez une annonce a partir le nav bar en haut';
    }
    if (stripos($message, 'creation') !== false ) {
        $responseMessage = 'Vous pouvez creez un compte et participer en different activite dans ce site';
    }
    
    // If no response message was set, provide a generic response
    if (empty($responseMessage)) {
        $responseMessage = "desole j ai pas compris ton message";
    }

    // Return response
    return new JsonResponse($responseMessage);
        }

}
