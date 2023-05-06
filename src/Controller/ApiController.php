<?php

namespace App\Controller;

use  App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer ;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mime\Address;
class ApiController extends AbstractController
{
    
    #[Route('/adduserjson', name: 'app_ajout_user_json', methods: ['GET', 'POST'])]
    public function addDonJson(Request $req, SerializerInterface $serializer,UserPasswordHasherInterface $passwordEncoder): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        $user = new User();

        $user->setNom($req->get('nom'));
        $user->setPrenom($req->get('prenom'));
        $user->setEmail($req->get('email'));
        $user->setPassword(
            $passwordEncoder->hashPassword(
                $user,
                $req->get('password')
            )
        );
        
        $user->setNumTelephone($req->get('numTelephone'));
        $user->setType($req->get('type'));
        $user->setImage($req->get('image'));
        $user->setRoles(['ROLE_USER']);
       $user ->setScore(0);
       $user->setNbEtoile(0);
       $user->setBlocked(0);
       
        $entityManager->persist($user);
        $entityManager->flush();

        $json = $serializer->serialize($user, "json", ["groups" => "event:read"]);
        return new Response("User added" . json_encode($json));
    }
   
    #[Route('/allUsersss', name: 'app_list_users_json', methods: ['GET', 'POST'])]
    public function showUsers(Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer): Response
    {
        
            $users=$repo->findAll();

            $userNormalises=$normalizer->normalize($users,"json",["groups" => "user"]);

            $json = json_encode($userNormalises);
        

      
        return new Response($json);
    }
    

    #[Route('/signin', name: 'app_list_user_json', methods: ['GET', 'POST'])]
    public function Signin(Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer,UserPasswordHasherInterface $passwordEncoder): Response
    {
        
        $email=$req->get('email');
        $password=$req->get('password');
            $user=$repo->findOneBy(['email'=> $email ]);
            $userNormalises=$normalizer->normalize($user,'json',['groups' => "user"]);
            $userNormalises['message']='success';

            if ($user==null) {
                $userNormalises['message']='failed';
                return new Response('Invalid email', Response::HTTP_BAD_REQUEST);
              
            }
            if (!$passwordEncoder->isPasswordValid($user, $password)) {
                $userNormalises['message']='failed';
                return new Response('Invalid password', Response::HTTP_BAD_REQUEST);
                
            }
           


            $json = json_encode($userNormalises);
        

      
        return new Response($json);
    }
    #[Route('/User/{id}', name: 'app_user_json', methods: ['GET', 'POST'])]
    public function UserId($id,Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer)
    {
        
            $user=$repo->find($id);
            $userNormalises= $normalizer->normalize($user,'json',['groups' =>'user']);
      
        return new Response(json_encode($userNormalises));
    }
    #[Route('/userbyemail/{email}', name: 'app_get_user_json', methods: ['GET'])]
public function getUserByEmail(Request $request, SerializerInterface $serializer, UserRepository $userRepository, NormalizerInterface $normalizer): Response
{
    $id = $request->attributes->get('email');
    $user = $userRepository->findOneBy(['email' => $id]);

    if (!$user) {
        return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
    }

    $userNormalized = $normalizer->normalize($user, "json", ["groups" => "user"]);
    $json = json_encode($userNormalized);

    return new Response($json);
}
    #[Route('/UpdateUser/{id}', name: 'app_updateuser_json', methods: ['GET', 'POST'])]
    public function UpdateUser($id,Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(user::class)->find($id);
        $user->setNom($req->get('nom'));
        $user->setPrenom($req->get('email'));
        $em->flush();
      
        $jsonContent= $normalizer->normalize($user,'json',['groups' =>'user']);
        return new Response("User updated",json_encode($jsonContent));

    }

    #[Route('/deleteUser/{id}', name: 'app_deleteuserr_json', methods: ['GET','POST'])]
    public function DeleteUser($id,Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $repo->remove($user);
        $entityManager->flush();

        $json = $serializer->serialize($user, "json", ["groups" => "dons"]);
        return new Response("event deleted" . json_encode($json));

    }
    #[Route('/blockuser/{id}', name: 'app_block_json', methods: ['GET','POST'])]
    public function blockuser($id,Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
       $user->setBlocked(1);
       $entityManager->Persist($user);
        $entityManager->flush();

        $json = $serializer->serialize($user, "json", ["groups" => "dons"]);
        return new Response("event deleted" . json_encode($json));

    }
    #[Route('/resetpassword', name: 'app_email_json', methods: ['GET','POST'])]
    public function resetPassword(Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer,EntityManagerInterface $entityManager)
    {
        /* $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $repo->remove($user);
        $entityManager->flush(); */

       /*  $json = $serializer->serialize($user, "json", ["groups" => "dons"]);
        return new Response("event deleted" . json_encode($json)); */
        $email=$req->get('email');
       // dd($email);
        $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['email' => $email]);
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

    
}
