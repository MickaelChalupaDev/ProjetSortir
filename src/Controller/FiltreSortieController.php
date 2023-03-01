<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\User;
use App\Form\FormFiltreSortiesType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Sortie;
use Symfony\Component\Routing\Annotation\Route;

class FiltreSortieController extends AbstractController
{
    #[Route('/FiltrerSortie', name: 'app_filtre_sortie')]
    public function index(Request $request, SortieRepository $repository): Response
    {


        $form = $this->createForm(FormFiltreSortiesType::class);
        // Récupérer les sorties en fonction des filtres
        $sorties = $repository->findSortiesByFilters
        (
            $request->query->get('campus'),
            $request->query->get('nom'),
            $request->query->get('dateHeureDebut'),
            $request->query->get('dateLimiteInscription'),
            $request->query->get('organisateur'),
           // $request->query->get('passees')
        );

        // Afficher les sorties
        return $this->render('filtrez_les_sorties/index.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView(),
        ]);
    }
}

