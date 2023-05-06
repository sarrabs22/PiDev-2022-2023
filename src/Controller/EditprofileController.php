<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UpdateFormType;
use App\Form\ImageUpdateType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EditprofileController extends AbstractController
{
    
    #[Route('/editprofile', name: 'app_editprofile')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userepo,SessionInterface $session): Response
    {




// Mettez à jour les propriétés de l'entité


        //Getting user
       $user=$this->getUser();
      
    
         //////
         $user = $entityManager
         ->getRepository(User::class)
         ->findOneBy(['id' => $user->getUserIdentifier()]);
       
        $userimage=$user->getImage();
        
        $form = $this->createForm(UpdateFormType::class,$user);
        $form->handleRequest($request);


        
        //dd(basename($user->getImage()));
       
        $imageForm = $this->createForm(ImageUpdateType::class);
        $imageForm->handleRequest($request);

        $message = null;
      
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
          // Handle image form separately
          $uploadedFile = $imageForm->get('image')->getData();

          if ($uploadedFile) {
              $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
              $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
              $newFile = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
              $uploadedFile->move(
                  $destination,
                  $newFile
              );
              $user->setImage($newFile);
              $entityManager->flush();
              $message = 'Image ajouté';
          }
      }
      
        if ($form->isSubmitted()  && $form->isValid()) {
             
           
            
           // $user->setImage($userimage);
           // dd($userimage);
          
           
           // $entityManager->persist($user);
             $entityManager->flush(); 
             $message = 'Le profil est mis a jour avec succés';
             return $this->render('editprofile/index.html.twig', [
              'controller_name' => 'EditprofileController',
              'UpdateForm' => $form->createView(),
              'user' =>$user,
              'message' => $message,
              'UserImageForm' => $imageForm->createView(),
          ]);
        
            

    }
    return $this->render('editprofile/index.html.twig', [
        'controller_name' => 'EditprofileController',
        'UpdateForm' => $form->createView(),
        'user' =>$user,
        'message' => $message,
        'UserImageForm' => $imageForm->createView(),
    ]);
}
    
}
