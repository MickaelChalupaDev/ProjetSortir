<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
// use App\Form\UserformType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{
    #[Route('/monProfil', name: 'user_profil')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
       // $user = new User();
        $user=$this->getUser();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()){

            $user = $userForm->getData();
            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            //dd($data);
              $entityManager->persist($user);
              $entityManager->flush();
              $this->addFlash('success','Profil modifié avec succès !');
              return $this->redirectToRoute('app_filtre_sortie');
        }

        return $this->render('user/monProfil.html.twig', [
            'userForm' => $userForm->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/autreProfil/{id}', name: 'autre_profil')]
    public function voir(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user=$entityManager->getRepository(User::class)->find($id);
        return $this->render('user/autreProfil.html.twig', [ 'user' => $user]);
    }

    // #[Route('/newuser', name: 'new_user')]
    // public function newuser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    // {
    //    // $user = new User();
    //     $userForm = $this->createForm(UserFormType::class);
    //     $userForm->handleRequest($request);
    //     if ($userForm->isSubmitted() && $userForm->isValid()){
    //             $user = $userForm->getData();
    //             $password = $hasher->hashPassword($user, $user->getPassword());
    //             $user->setPassword($password);
    //             $user->setActif(false);
            
    //         //dd($data);
    //           $entityManager->persist($user);
    //           $entityManager->flush();
    //           $this->addFlash('success','Profil ajouté avec succès !');
    //           return $this->redirectToRoute('main_home');
    //     }

    //     return $this->render('user/newuser.html.twig', [
    //         'userForm' => $userForm->createView(),
    //     ]);
    // }
}
