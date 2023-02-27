<?php

namespace App\Controller;

use App\Entity\Exploit;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Contracts\Translation\TranslatorInterface;



class RegistrationController extends AbstractController
{
    

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
         $exploit1 = new Exploit();
        
        $form = $this->createForm(RegistrationFormType::class,$user);
        $form->handleRequest($request);
       
      
        if ($form->isSubmitted() && $form->isValid() ) {
            // encode the plain password
            $userData = $form->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $userData->getPassword()
                )
               
            );
            /** @var UploadedFile $uploadedFile */
            $uploadedFile=$form['image']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFile = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFile
               );
               $user->setImage($newFile);
             
            
               $user->setRoles(['ROLE_USER']);
            $user->setScore(0);
            $user->setNbEtoile(0);
            $user->setType("donneur");
            $user->addExploit($exploit1);
            $entityManager->persist($exploit1);
            $entityManager->persist($user);
            $entityManager->flush();
            
               
               
            return $this->redirectToRoute('app_login_signup');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            "exploit" => $exploit1,
            "user" => $user ,
        
        ]);
    }

    

       
    
}
