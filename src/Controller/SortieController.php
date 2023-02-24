<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/modifierSortie/{id}', name: 'app_sortie')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('main_home');
        }
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->get('latitude')->setData($sortie->getLieu()->getLatitude());
        $sortieForm->get('longitude')->setData($sortie->getLieu()->getLongitude());
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){

            if ($sortieForm->get('enregistrer')->isClicked()) {
                $sortie = $sortieForm->getData();
                $latitude = $sortieForm->get('latitude')->getData();
                $longitude = $sortieForm->get('longitude')->getData();
                $lieu = $sortie->getLieu();
                $lieu->setLatitude($latitude);
                $lieu->setLongitude($longitude);
                $sortie->setLieu($lieu);

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie modifiée avec succès !');
                return $this->redirectToRoute('main_home');
            }else {
                $sortie = $sortieForm->getData();
                $entityManager->remove($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie suprimée avec succès !');
                return $this->redirectToRoute('main_home');
            }
        }

        return $this->render('sortie/maSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' =>$sortie,
        ]);
    }


    #[Route('/AfficherSortie/{id}', name: 'app_afficherSortie')]
    public function afficher(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('main_home');
        }
        $users=$sortie->getUsers();
       foreach ($users as $user)
        {
            $user->getNom();
        }

      /*  dd($users);*/
        return $this->render('sortie/afficherSortie.html.twig', [
                       'sortie' =>$sortie,
        ]);
    }
}
