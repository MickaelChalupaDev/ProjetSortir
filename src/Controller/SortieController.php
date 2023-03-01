<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use DateTime;
use App\Entity\Lieu;
use App\Entity\Sortie;
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
    #[Route('/modifierSortie/{id}', name: 'app_sortie')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('main_home');
        }
        if ($this->getUser() !== $sortie->getOrganisateur()){
            $this->addFlash('fail', ' Vous n\'êtes pas l\'organisateur de cette Sortie !');
            return $this->redirectToRoute('main_home');
        }
        $etat= new Etat();
        $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Créée']);
        if ($sortie->getEtat() !== $etat  ){
            $this->addFlash('fail', ' Cette sortie n\'est plus modifiable !');
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
                $this->addFlash('success', 'Sortie a été modifiée avec succès !');
                return $this->redirectToRoute('main_home');
            }elseif ($sortieForm->get('supprimer')->isClicked()) {
                $sortie = $sortieForm->getData();
                $entityManager->remove($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie a été supprimée avec succès !');
                return $this->redirectToRoute('main_home');
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
                return $this->redirectToRoute('main_home');

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
        $creationSortieForm->handleRequest($request);

        if ($creationSortieForm->isSubmitted() && $creationSortieForm->isValid()){

            if ($creationSortieForm->get('enregistrer')->isClicked()) {
                $sortie = $creationSortieForm->getData();
                $latitude = $creationSortieForm->get('latitude')->getData();
                $longitude = $creationSortieForm->get('longitude')->getData();
                $lieu = $sortie->getLieu();
                $lieu->setLatitude($latitude);
                $lieu->setLongitude($longitude);
                $sortie->setLieu($lieu);
                $user=$this->getUser();
                $sortie->setOrganisateur($user);
                $etat= new Etat();
                $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Créée']);
                $sortie->setEtat($etat);

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie a été crée avec succès !');
                return $this->redirectToRoute('main_home');
            } else {
                $sortie = $creationSortieForm->getData();
                $latitude = $creationSortieForm->get('latitude')->getData();
                $longitude = $creationSortieForm->get('longitude')->getData();
                $lieu = $sortie->getLieu();
                $lieu->setLatitude($latitude);
                $lieu->setLongitude($longitude);
                $sortie->setLieu($lieu);
                $user=$this->getUser();
                $sortie->setOrganisateur($user);
                $etat= new Etat();
                $etat=$entityManager->getRepository($etat::class)->findOneBy(['libelle'=>'Ouverte']);
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Sortie a été publiée avec succès !');
                return $this->redirectToRoute('main_home');

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
            return $this->redirectToRoute('main_home');
        }
        $unMoisAuparavant = new DateTime();
        $unMoisAuparavant->modify('-1 month');
        if($sortie->getDateHeureDebut() <= $unMoisAuparavant) {
            $this->addFlash('fail', ' Cette sortie n\'est plus accesible !');
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


    #[Route('/sInscrire/{id}', name: 'app_sInscrire')]
    public function Sinscrire(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('main_home');
        }
        $user=$this->getUser();
        $sortie->addUser($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'Vous êtes inscrit avec succès !');

        return $this->redirectToRoute('main_home');
    }

    #[Route('/seDesister/{id}', name: 'app_seDesister')]
    public function SeDesister(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('main_home');
        }
        $user=$this->getUser();
        $sortie->removeUser($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'Vous êtes désinscrit avec succès !');

        return $this->redirectToRoute('main_home');
    }


    #[Route('/annulerSortie/{id}', name: 'app_annuler')]
    public function annuler(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sortie::class)->find($id);
        if(!$sortie) {
            $this->addFlash('fail', 'Sortie n\'existe pas !');
            return $this->redirectToRoute('main_home');
        }
        if ($this->getUser() !== $sortie->getOrganisateur()){
            $this->addFlash('fail', ' Vous n\'êtes pas l\'organisateur de cette Sortie !');
            return $this->redirectToRoute('main_home');
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
            return $this->redirectToRoute('main_home');
            }
        return $this->render('sortie/annulerSortie.html.twig', [
            'annulerSortieForm' => $annulerSortieForm->createView(),
            'sortie' =>$sortie,
        ]);
    }
}
