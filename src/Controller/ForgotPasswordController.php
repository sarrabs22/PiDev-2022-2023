<?php

namespace App\Controller;
use App\Entity\User ;
use App\Form\ResetFormType;
use App\Form\ResetformpasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer ;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class ForgotPasswordController extends AbstractController
{
    #[Route('/forgotPassword', name: 'app_forgot_password')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $user=$this->getUser();
      
        $form = $this->createForm(ResetFormType::class);
        $form->handleRequest($request);
        

        if ($form->isSubmitted()) {
            $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['email' => $form->get('email')->getData()]);
            $loginUrl = $this->generateUrl('app_reset', [
                'userEmail' => $user->getEmail(), 
           ], UrlGeneratorInterface::ABSOLUTE_URL);
   
   
           $transport = Transport::fromDsn('smtp://genereux.scaredtocompile@gmail.com:qxrosldtlgtwnpky@smtp.gmail.com:587');
           $mailer= new Mailer($transport);
         $email= (new TemplatedEmail())
          ->from(new Address('genereux.scaredtocompile@gmail.com', 'ScaredToCompileBot'))
          ->to($user->getEmail())
          ->subject('Please Confirm your Email')
          ->html("<p>Hello,</p><p>Please click the following link to confirm your email address and be redirected to the login page:</p><p><a href=\"$loginUrl\">$loginUrl</a></p>");
          
          // do anything else you need here, like send an email
          $mailer->send($email);
        }
        
       return $this->render('forgot_password/emailreset.html.twig', [
        'controller_name' => 'ForgotPasswordController',
        'resetForm' => $form->createView(),
        
    ]);


        
    }

     #[Route('/reset', name: 'app_reset')] 
     public function index2(EntityManagerInterface $entityManager,Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $userEmail=$request->get('userEmail');
        $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['email' => $userEmail]);
        
        $form = $this->createForm(ResetformpasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

        }
        $entityManager->persist($user);
            $entityManager->flush();
        return $this->render('forgot_password/index.html.twig', [
            'controller_name' => 'ForgotPasswordController',
            'resetForm' => $form->createView(),
        ]);
    }
 
}