<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer ;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
class MailerController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(): Response
    {


         $transport = Transport::fromDsn('smtp://genereux.scaredtocompile@gmail.com:qxrosldtlgtwnpky@smtp.gmail.com:587');
            $mailer= new Mailer($transport);
    
            $email=(new TemplatedEmail());
    
            $email->from('genereux.scaredtocompile@gmail.com');
            $email->to('dhafer.souid@esprit.tn');
            $email->htmlTemplate('confirmationEmail.html.twig');
            $email->html('<p> salut test <p>');
            /* $this->renderView(
                // templates/hello/email.txt.twig
                'hello/email.txt.twig',
                ['name' => $name]
            ) */
    
            $mailer->send($email);
        
        return $this->render('test/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
    #[Route('/verifieremail', name: 'app_verifieremail')]
    public function index2(): Response
    {


      
        
        return $this->render('mailer/verifier.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
}
