<?php

namespace App\Controller\Api;

use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/api')]
class ApiVilleController extends AbstractController
{
    #[Route('/villes', name: 'api_villes_liste', methods: ['GET'])]
    public function listeVille(VilleRepository $villeRepository): JsonResponse{

        $villes = $villeRepository->findAll();
        return $this->json($villes, Response::HTTP_OK, [],['groups'=>'liste_villes']);
    }
    #[Route('/ville/{id}', name: 'api_ville', methods: ['GET'])]
    public function uneVille(int $id, VilleRepository $villeRepository): JsonResponse{

        $ville = $villeRepository->find($id);
        return $this->json($ville, Response::HTTP_OK, [],['groups'=>'liste_villes']);
    }
    #[Route('/lieux/{id}', name: 'api_lieux_liste', methods: ['GET'])]
    public function listeLieu(int $id,VilleRepository $villeRepository): JsonResponse{

        $lieux = $villeRepository->find($id)->getLieux();
        return $this->json($lieux, Response::HTTP_OK, [],['groups'=>'liste_villes']);
    }

    #[Route('/rue/{id}', name: 'api_rueCodePostal', methods: ['GET'])]
    public function rueCodePostal(int $id,LieuRepository $lieuRepository): JsonResponse{

        $lieu = $lieuRepository->find($id);
        return $this->json($lieu, Response::HTTP_OK, [],['groups'=>'liste_villes']);
    }

}