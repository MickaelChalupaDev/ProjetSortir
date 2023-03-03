<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\User;
use App\Form\FormFiltreSortiesType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Sortie;
use Symfony\Component\Routing\Annotation\Route;

class FiltreSortieController extends AbstractController
{
    #[Route('/FiltrerSortie', name: 'app_filtre_sortie')]
    public function index(Request $request, SortieRepository $repository, EtatRepository $etatRepository): Response
    {
        $this->forward('App\Controller\checkController::updateSortiesEtat');
        $sortie= new Sortie();
        $form = $this->createForm(FormFiltreSortiesType::class, $sortie);

        $form->handleRequest($request);
        // Récupérer les sorties en fonction des filtres

        if ($form->isSubmitted()) {
            $sortie=$form->getData();
            $campus = $sortie->getCampus();

            $nom=$form->get('nom')->getData();

            $entre=$form->get('entre')->getData();

            $et=$form->get('et')->getData();

            $organisateur= new User();
            $participant= new User();
            $etat= new Etat();
            $val=false;
            //dump($form->get('S0')->getData());
            if ($form->get('S0')->getData()) { $organisateur=$this->getUser();}
            if ($form->get('S1')->getData()) { $participant=$this->getUser();$val=true;}
            if ($form->get('S2')->getData()) { $participant=$this->getUser();$val=false;}
            if ($form->get('S3')->getData()) { $etat=$etatRepository->findOneBy(['libelle'=>'passée']); }


            $sorties = $repository->findSortiesByFilters($campus,$nom,$entre,$et,$organisateur, $participant, $val, $etat);


            return $this->render('filtrez_les_sorties/index.html.twig', [
                'sorties' => $sorties,
                'form' => $form->createView(),
            ]);
        }



            // Afficher les sorties
            return $this->render('filtrez_les_sorties/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }





}



