<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer ;
class MailerController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(): Response
    {


        $transport = Transport::fromDsn('smtp://genereux.scaredtocompile@gmail.com:qxrosldtlgtwnpky@smtp.gmail.com:587');
        $mailer= new Mailer($transport);

        $email=(new Email());

        $email->from('genereux.scaredtocompile@gmail.com');
        $email->to(
            'benromdhane.aziz@esprit.tn'

        );
        $email->subject('C est scared to compile');
        $email->text('Mon message');

        $email->html('
        <h1> SIUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU </h1>
        ');

        $mailer->send($email);
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
}
