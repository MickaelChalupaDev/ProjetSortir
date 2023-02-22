<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusGestionController extends AbstractController
{
    #[Route('/campus/gestion', name: 'app_campus_gestion')]
    public function index(CampusRepository $campusRepository): Response
    {
        $search = $campusRepository->findAll();

        return $this->render('campus_gestion/campus.html.twig', [
            'search' => $search,
        ]);
    }
}
