<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\AjoutCampusType;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CampusGestionController extends AbstractController
{
    #[Route('/campus/gestion', name: 'app_campus_gestion')]

    public function index(Request $request, CampusRepository $campusRepository, EntityManagerInterface $entityManager): Response
    {

        $c = new Campus();
        $campusForm = $this->createForm(CampusType::class, $c);
        $campusForm->handleRequest($request);

       if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $search = $campusRepository->searchCampus(['nom' => $c->getNom()]);
        }

       $a = new Campus();
         $ajoutCampusForm = $this->createForm(AjoutCampusType::class, $a);
            $ajoutCampusForm->handleRequest($request);

        if ($ajoutCampusForm->isSubmitted() && $ajoutCampusForm->isValid()) {
            $ajout = $ajoutCampusForm->getData();
            $entityManager->persist($ajout);
            $entityManager->flush();

            $this->addFlash('success', 'Le campus a bien été ajouté');
            return $this->redirectToRoute('app_campus_gestion');
        }




        return $this->render('campus_gestion/campus.html.twig', [
            'search' => $search ?? null,
            'campusForm' => $campusForm->createView(),
            'ajout' => $ajout ?? null,
            'ajoutCampusForm' => $ajoutCampusForm->createView(),
        ]);
    }
}
