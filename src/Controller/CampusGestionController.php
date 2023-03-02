<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\AjoutCampusType;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusGestionController extends AbstractController
{
    #[Route('/admin/campus/gestion', name: 'app_campus_gestion')]
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


    #[Route('/admin/campus/gestion/{id}', name: 'app_campus_delete')]
    public function delete(int $id, EntityManagerInterface $em): RedirectResponse
    {
        $campus = $em->getRepository(Campus::class)->find($id);
        $em->remove($campus);
        $em->flush();

        $this->addFlash('success', 'Le campus a bien été supprimé');
        return $this->redirectToRoute('app_campus_gestion');
    }
}
