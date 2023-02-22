<?php

namespace App\Controller;

use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityGestionController extends AbstractController
{
    #[Route('/city/gestion', name: 'app_city_gestion')]
    public function index(VilleRepository $villeRepository): Response
    {
        $search = $villeRepository->findAll();
        return $this->render('city_gestion/cities.html.twig', [
            'search' => $search,
        ]);
    }
}
