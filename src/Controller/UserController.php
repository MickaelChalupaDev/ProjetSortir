<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'user_profil')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user=$this->getUser();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted()&& $userForm->isValid()){
            if($userForm->getClickedButton() && 'annuler' === $userForm->getClickedButton()->getName()){
              return $this->render('main/home.html.twig');
            } else{
                dump($user);
             //   $entityManager->persist($user);
              //  $entityManager->flush();
                return $this->render('main/home.html.twig');
            }
        }
        return $this->render('user/monProfil.html.twig', [
            'userForm' => $userForm->createView(),
            'user' => $user,
        ]);
    }
}
