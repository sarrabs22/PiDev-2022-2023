<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use BotMan\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;

class BotmanController extends AbstractController
{
    #[Route('/botman', name: 'app_botman')]
    public function index(): Response
    {
        $botman = $this->get('botman');

        // Define your chatbot logic here

        $botman->hears('hi', function (BotMan $bot) {
            $bot->reply('Hello!');
        });
        
        $botman->listen();

        return new Response();
    }
}
