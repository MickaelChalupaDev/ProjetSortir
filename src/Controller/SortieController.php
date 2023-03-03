<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use DateTime;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\AnnulerSortieType;
use App\Form\CreationSortieType;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/publierSortie/{id}', name: 'app_publier_sortie')]
    public function publier(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        $etat= new Etat();
        $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Ouverte']);
        $sortie->setEtat($etat);
        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'Sortie a été publiée avec succès !');
        return $this->redirectToRoute('app_filtre_sortie');
    }
    #[Route('/modifierSortie/{id}', name: 'app_sortie')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('app_filtre_sortie');
        }
        if ($this->getUser() !== $sortie->getOrganisateur()){
            $this->addFlash('fail', ' Vous n\'êtes pas l\'organisateur de cette Sortie !');
            return $this->redirectToRoute('app_filtre_sortie');
        }
        $etat= new Etat();
        $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Créée']);
        if ($sortie->getEtat() !== $etat  ){
            $this->addFlash('fail', ' Cette sortie n\'est plus modifiable !');
            return $this->redirectToRoute('app_filtre_sortie');
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
                $this->addFlash('success', 'Sortie a été modifiée avec succès !');
                return $this->redirectToRoute('app_filtre_sortie');
            }elseif ($sortieForm->get('supprimer')->isClicked()) {
                $sortie = $sortieForm->getData();
                $entityManager->remove($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie a été supprimée avec succès !');
                return $this->redirectToRoute('app_filtre_sortie');
            } else {
                $sortie = $sortieForm->getData();
                $latitude = $sortieForm->get('latitude')->getData();
                $longitude = $sortieForm->get('longitude')->getData();
                $lieu = $sortie->getLieu();
                $lieu->setLatitude($latitude);
                $lieu->setLongitude($longitude);
                $sortie->setLieu($lieu);
                 $etat= new Etat();
                 $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Ouverte']);
                 $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie a été publiée avec succès !');
                return $this->redirectToRoute('app_filtre_sortie');

            }
        }

        return $this->render('sortie/maSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' =>$sortie,
        ]);
    }

    #[Route('/creerSortie', name: 'app_creation_sortie')]
    public function creation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie= new Sortie();
        $creationSortieForm = $this->createForm(CreationSortieType::class, $sortie);
        $user=$this->getUser();
        $sortie->setCampus($user->getCampus());
        $creationSortieForm->handleRequest($request);



        if ($creationSortieForm->isSubmitted() && $creationSortieForm->isValid()){

            if ($creationSortieForm->get('enregistrer')->isClicked()) {
                $sortie = $creationSortieForm->getData();

                $sortie->getLieu()->setLatitude($creationSortieForm->get('latitude')->getData());
                $sortie->getLieu()->setLongitude($creationSortieForm->get('longitude')->getData());

                $sortie->setOrganisateur($user);

                $etat= new Etat();
                $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Créée']);
                $sortie->setEtat($etat);

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie a été crée avec succès !');
                return $this->redirectToRoute('app_filtre_sortie');
            } else {
                $sortie = $creationSortieForm->getData();

                $sortie->getLieu()->setLatitude($creationSortieForm->get('latitude')->getData());
                $sortie->getLieu()->setLongitude($creationSortieForm->get('longitude')->getData());

                $sortie->setOrganisateur($user);

                $etat= new Etat();
                $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Ouverte']);
                $sortie->setEtat($etat);

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie a été publiée avec succès !');
                return $this->redirectToRoute('app_filtre_sortie');

            }
        }

        return $this->render('sortie/creerSortie.html.twig', [
            'creationSortieForm' => $creationSortieForm->createView(),
            'sortie' =>$sortie,
        ]);
    }


    #[Route('/afficherSortie/{id}', name: 'app_afficherSortie')]
    public function afficher(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('app_filtre_sortie');
        }
        $unMoisAuparavant = new DateTime();
        $unMoisAuparavant->modify('-30 day');

/*
        if($sortie->getDateHeureDebut() <= $unMoisAuparavant) {
            $this->addFlash('fail', ' Cette sortie n\'est plus accessible !');
            return $this->redirectToRoute('app_filtre_sortie');
        }*/
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


    #[Route('/sInscrire/{id}', name: 'app_sInscrire')]
    public function Sinscrire(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('app_filtre_sortie');
        }
        $user=$this->getUser();
        if ($user==$sortie->getOrganisateur()){
            $this->addFlash('fail', 'Vous êtes l\'organisateur de cette sortie !');

            return $this->redirectToRoute('app_filtre_sortie');
        }
        $sortie->addUser($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'Vous êtes inscrit avec succès !');

        return $this->redirectToRoute('app_filtre_sortie');
    }

    #[Route('/seDesister/{id}', name: 'app_seDesister')]
    public function SeDesister(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('app_filtre_sortie');
        }
        $user=$this->getUser();
        $sortie->removeUser($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'Vous êtes désinscrit avec succès !');

        return $this->redirectToRoute('app_filtre_sortie');
    }


    #[Route('/annulerSortie/{id}', name: 'app_annuler')]
    public function annuler(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('app_filtre_sortie');
        }
        if ($this->getUser() !== $sortie->getOrganisateur()){
            $this->addFlash('fail', ' Vous n\'êtes pas l\'organisateur de cette Sortie !');
            return $this->redirectToRoute('app_filtre_sortie');
        }
        $annulerSortieForm = $this->createForm(AnnulerSortieType::class, $sortie);
        $annulerSortieForm->handleRequest($request);
        if ($annulerSortieForm->isSubmitted() && $annulerSortieForm->isValid()){

            $sortie = $annulerSortieForm->getData();
            $etat= new Etat();
            $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Annulée']);
            $sortie->setEtat($etat);

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie annulée avec succès !');
            return $this->redirectToRoute('app_filtre_sortie');
            }
        return $this->render('sortie/annulerSortie.html.twig', [
            'annulerSortieForm' => $annulerSortieForm->createView(),
            'sortie' =>$sortie,
        ]);
    }
}
