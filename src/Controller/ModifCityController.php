<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\ModifCityType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifCityController extends AbstractController
{
    #[Route('/modif/city/{id}', name: 'app_modif_city')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $m = $entityManager->getRepository(Ville::class)->find($id);
        $modifForm = $this->createForm(ModifCityType::class, $m);
        $modifForm->handleRequest($request);

        if ($modifForm->isSubmitted() && $modifForm->isValid()) {
            $m = $modifForm->getData();
            $entityManager->persist($m);
            $entityManager->flush();


            $this->addFlash('success', 'La ville a bien été modifiée');
            return $this->redirectToRoute('app_city_gestion');
        }


        return $this->render('modif_city/modfif_city.html.twig',
        [
            'modifForm' => $modifForm->createView(),
        ]);
    }
}
