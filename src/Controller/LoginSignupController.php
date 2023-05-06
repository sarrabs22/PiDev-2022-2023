<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationFormType;
use App\Entity\User;
use App\Form\LoginFormType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MyService;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
/* use \Symfony\Bundle\SecurityBundle\Security; */
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginSignupController extends AbstractController
{

    #[Route('/login', name: 'app_login_signup')]
    public function index(Request $request,EntityManagerInterface $entityManager,MyService $logindata,AuthenticationUtils $authenticationUtils,SessionInterface $session): Response
    {
        /* $logindata->email='';
        $logindata->password='';
        $form = $this->createForm(LoginFormType::class,$logindata);
        $form->handleRequest($request); */
       
        /* $error= 0 ; // checks if user did put right login info */
        $show_refresh=0;
        $error = $authenticationUtils->getLastAuthenticationError();
       // Get the authenticated user object
      
        if ($error instanceof AuthenticationException) {
            $errorMessage = 'mot de passe ou email invalide';
            
        } else {
            $errorMessage = null;
        }
            /*  $user = $security->getUser();
 */
      
       
        $email = $authenticationUtils->getLastUsername();
       
        if($this->isGranted('ROLE_USER'))
        {
            $user=$this->getUser();
        $user2 = $entityManager
        ->getRepository(User::class)
        ->find($user->getUserIdentifier());
      //  if($user2->isVerified() == true )
      //  {
              
            return $this->redirectToRoute('app_test');
       // }
       //  else {
      //      return $this->redirectToRoute('app_verifieremail');
      //  }  
     }
        // get the number of login attempts from the session
       
        $loginAttempts = $session->get('login_attempts', 0);
        // increment the login attempts count
        $loginAttempts++;

        // save the updated login attempts count in the session
        $session->set('login_attempts', $loginAttempts);
        

        
        
        /* if(!$error && $email )
        {
            $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['email' => $email]);
        $user = $entityManager
            ->getRepository(User::class)
            ->find($this->getUser()->getUserIdentifier()); */

       
     
         
       
    
    if ($this->isGranted('ROLE_ADMIN')) {
    
        return $this->redirectToRoute('app_admin');
        
    }
    
    if ($this->isGranted('ROLE_USER')) {
        
    }
        // check if the user has exceeded the maximum number of login attempts
        $maxAttempts = 3;
        $remainingAttempts = $maxAttempts - $loginAttempts;
        
        $form_appear=1;

        if ($loginAttempts >= 3) {
            $remainingTime = $session->get('remaining_time');

            if ($remainingTime === null || $remainingTime <= time()) {
                $remainingTime = time() + 50;
                $session->set('remaining_time', $remainingTime);
                $session->set('login_attempts', 0);
                $session->save();
                $form_appear=1;
            } else {
                $remainingSeconds = $remainingTime - time();
                $remainingMinutes = (int) ($remainingSeconds / 60);
                $remainingSeconds = $remainingSeconds % 60;
                $form_appear=0;
                $errorMessage = sprintf('Beaucoup de tentative essayez de nouveau dans  %d secondes.', $remainingSeconds);
                $show_refresh=1;
                return $this->render('login_signup/index.html.twig', [
                    'error' => $errorMessage,
                    'email' => $email,
            'remaining_attempts' => $remainingAttempts,
            'max_attempts' => $maxAttempts,
            'form_appear' => $form_appear,
            'showRefresh' => $show_refresh,
                ]);
            }
        }

      
        return $this->render('login_signup/index.html.twig', [
            'controller_name' => 'LoginSignupController',
            /* 'loginForm' => $form->createView(), */
            'error' => $errorMessage,
            'email' => $email,
            'remaining_attempts' => $remainingAttempts,
            'max_attempts' => $maxAttempts,
            'form_appear' => $form_appear,
            'showRefresh' => $show_refresh,
        ]);

        
     /* $error = null;
        $lastUsername = $session->get('_security.last_username');

        if ($remainingAttempts <= 0) {
            $error = 'Too many failed login attempts. Please try again later.';
        } else {
            $error = $this->getAuthenticationError($request);
        } */

        
    


       /*  if ($request->isMethod('POST')) {
            // increment the number of login attempts
            $session->set('login_attempts', $loginAttempts + 1);
        } */
       /*  if ($form->isSubmitted() && $form->isValid() ) { */

           
           /*  $this->addFlash('user empty', 'User null');
            $email = $form->get('email')->getData(); */
           
            /* $user = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]); */

            


            /* if ($user === null) {
                // User does not exist
                $this->addFlash('user empty', 'User null');
                $error=1;
                
            }
            else
            {

            $this->addFlash('user added', 'User added');
            $session = $request->getSession();
            $session->set('userid', $user);
            return $this->redirectToRoute('app_test');
            } */

            
       /*  } */
    
    /* private function getAuthenticationError(Request $request)
    {
        


        $authenticationUtils = $this->authenticationUtils;
        $security = $this->getUser();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        if (!$error) {
            // If there is no login error, check if the previous request had an error message.
            $error = $request->getSession()->get(Security::AUTHENTICATION_ERROR);
        }

        if ($error) {
            // clear the error message from the session
            $request->getSession()->remove(Security::AUTHENTICATION_ERROR);
        }*/
        } 
}
