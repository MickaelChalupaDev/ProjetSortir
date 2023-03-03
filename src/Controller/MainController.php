<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Security $security): Response
    {
        // Si l'utilisateur est connecté, on redirige vers le contrôleur SortieController
        if ($security->getUser()) {
            return $this->redirectToRoute('app_filtre_sortie');
        }

        // Sinon, on redirige vers le contrôleur SecurityController
        return $this->redirectToRoute('app_login');
    }
}

