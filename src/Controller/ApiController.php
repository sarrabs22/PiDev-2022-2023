<?php

namespace App\Controller;

use  App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class ApiController extends AbstractController
{
    
    #[Route('/adduserjson', name: 'app_ajout_user_json', methods: ['GET', 'POST'])]
    public function addDonJson(Request $req, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        $user = new User();

        $user->setNom($req->get('nom'));
        $user->setPrenom($req->get('prenom'));
        $user->setEmail($req->get('email'));
        $user->setPassword($req->get('password'));
        $user->setNumTelephone($req->get('numTelephone'));
        $user->setType($req->get('type'));
        $user->setImage($req->get('image'));
       $user ->setScore(0);
       $user->setNbEtoile(0);

        $entityManager->persist($user);
        $entityManager->flush();

        $json = $serializer->serialize($user, "json", ["groups" => "event:read"]);
        return new Response("User added" . json_encode($json));
    }
    #[Route('/allUsers', name: 'app_list_user_json', methods: ['GET', 'POST'])]
    public function showUsers(Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer): Response
    {
        
            $users=$repo->findAll();

            $userNormalises=$normalizer->normalize($users,'json',['groups' => "user"]);

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
    #[Route('/UpdateUser/{id}', name: 'app_updateuser_json', methods: ['GET', 'POST'])]
    public function UpdateUser($id,Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(user::class)->find($id);
        $user->setNom($req->get('nom'));
        $user->setPrenom($req->get('prenom'));
        $em->flush();
      
        $jsonContent= $normalizer->normalize($user,'json',['groups' =>'user']);
        return new Response("User updated",json_encode($jsonContent));

    }

    #[Route('/deleteUser/{id}', name: 'app_deleteuser_json', methods: ['GET', 'POST'])]
    public function DeleteUser($id,Request $req, SerializerInterface $serializer,UserRepository $repo,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(user::class)->find($id);
        $em->remove($user);
        $em->flush();
      
        $jsonContent= $normalizer->normalize($user,'json',['groups' =>'user']);
        return new Response("User updated",json_encode($jsonContent));

    }

    
}
