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
       
        $config = [
            'hipchat_urls' => [
                'YOUR-INTEGRATION-URL-1',
                'YOUR-INTEGRATION-URL-2',
            ],
            'nexmo_key' => 'YOUR-NEXMO-APP-KEY',
            'nexmo_secret' => 'YOUR-NEXMO-APP-SECRET',
            'microsoft_bot_handle' => 'YOUR-MICROSOFT-BOT-HANDLE',
            'microsoft_app_id' => 'YOUR-MICROSOFT-APP-ID',
            'microsoft_app_key' => 'YOUR-MICROSOFT-APP-KEY',
            'slack_token' => 'xoxe.xoxp-1-Mi0yLTIzODM4MDMzNzUtNDg4MzQ3ODIwMzExMS00ODk3ODgyMjI1ODI3LTQ4OTUwNzQxODAxODEtYzkyZmU0MGE3MWMyOGI5ZjRhNGJlNjhmMzA2ZWUxMjg3MzRjMzM1NDE0YmVlOTYzMzZlNzBkNjExMjdhZjgxYg',
            'telegram_token' => 'YOUR-TELEGRAM-TOKEN-HERE',
            'facebook_token' => 'YOUR-FACEBOOK-TOKEN-HERE',
            'facebook_app_secret' => 'YOUR-FACEBOOK-APP-SECRET-HERE',
            'wechat_app_id' => 'YOUR-WECHAT-APP-ID',
            'wechat_app_key' => 'YOUR-WECHAT-APP-KEY',
        ];
       // create an instance
$botman = BotManFactory::create($config);

// give the bot something to listen for.
$botman->hears('hello', function (BotMan $bot) {
    $bot->reply('Hello yourself.');
});

// start listening
$botman->listen();
       
       // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        /* $profilePicture = $user->getImage(); */

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'config' => $config,
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
    if (stripos($message, 'don') !== false && stripos($message, 'donner') !== false) {
        $responseMessage = 'Vous pouvez donner un don a travers l interface don de l utilisateur si vous etes inscrits en tant que donneur';
    }
    if (stripos($message, 'don') !== false && stripos($message, 'recuperer') !== false) {
        $responseMessage = 'Vous pouvez recuperer un don a travers l interface de don si vous etes inscrits en tant que receveur';
    }
    if (stripos($message, 'aide') !== false ) {
        $responseMessage = 'Notre site vous offre la possiblite de recuperer ou donner des objets ,vous pouvez recuperer des objets ou donner des objets,il vous suffit simplement de saisir les informations de vos objets';
    }
    
    // If no response message was set, provide a generic response
    if (empty($responseMessage)) {
        $responseMessage = "desole j ai pas compris ton message";
    }

    // Return response
    return new JsonResponse($responseMessage);
        }

}
