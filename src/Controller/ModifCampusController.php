<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\ModifCampusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifCampusController extends AbstractController
{
    #[Route('/modif/campus/{id}', name: 'app_modif_campus')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $m = $entityManager->getRepository(Campus::class)->find($id);
        $modifCampForm = $this->createForm(ModifCampusType::class, $m);
        $modifCampForm->handleRequest($request);

        if ($modifCampForm->isSubmitted() && $modifCampForm->isValid()) {
            $m = $modifCampForm->getData();
            $entityManager->persist($m);
            $entityManager->flush();

            $this->addFlash('success', 'Le campus a bien été modifié');
            return $this->redirectToRoute('app_campus_gestion');
        }
        return $this->render('modif_campus/modif_campus.html.twig', [
            'modifCampForm' => $modifCampForm->createView(),
        ]);
    }
}
