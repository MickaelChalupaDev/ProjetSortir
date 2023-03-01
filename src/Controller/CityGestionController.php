<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\AjoutCityType;
use App\Form\CityType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;


class CityGestionController extends AbstractController
{
    #[Route('/city/gestion', name: 'app_city_gestion')]
    public function index(VilleRepository $villeRepository ,Request $request, EntityManagerInterface $entityManager): Response
    {

        $v = new Ville();

        $cityForm = $this->createForm(CityType::class, $v);
        $cityForm->handleRequest($request);

        if ($cityForm->isSubmitted() && $cityForm->isValid()) {

            $search = $villeRepository->searchCity(['nom' => $v->getNom()]);
        }

        $a = new Ville();
        $ajoutForm = $this->createForm(AjoutCityType::class, $a);
        $ajoutForm->handleRequest($request);

        if ($ajoutForm->isSubmitted() && $ajoutForm->isValid()) {
            $ajout = $ajoutForm->getData();
            $entityManager->persist($ajout);
            $entityManager->flush();


            $this->addFlash('success', 'La ville a bien été ajoutée');
            return $this->redirectToRoute('app_city_gestion');
        }

        return $this->render('city_gestion/cities.html.twig', [
            'remove' => $remove ?? null,
            'search' => $search ?? null,
            'searchCode' => $searchCode ?? null,
            'cityForm' => $cityForm->createView(),
            'ajout' => $ajout ?? null,
            'ajoutForm' => $ajoutForm->createView(),


        ]);
    }

//    #[Route('/city/gestion/remove/{id}', name: 'app_city_gestion_remove')]
//    public function remove(int $id, PersistenceManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
//    {
//        $em = $doctrine->getManager();
//        $villeRepository= $em->getRepository(Ville::class);
//        $remove = $villeRepository->find($id);
//        $entityManager->remove($remove);
//        $entityManager->flush();
//
//        $this->addFlash('success', 'La ville a bien été supprimée');
//        return $this->redirectToRoute('app_city_gestion');
//    }
}
