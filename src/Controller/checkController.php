<?php

namespace App\Controller;
use DateTime;
use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class checkController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function updateSortiesEtat()
    {
        $ajd = new DateTime(); 
        $unMoisAuparavant = new DateTime();
        $unMoisAuparavant->modify('-1 month');
        $sorties = $this->entityManager->getRepository(Sortie::class)->findAll();

        $etatcloture = $this->entityManager->getRepository(Etat::class)->find(3);
        $etatencourt = $this->entityManager->getRepository(Etat::class)->find(4);
        $etatarchiver = $this->entityManager->getRepository(Etat::class)->find(7);

        foreach ($sorties as $sortie) {
            $sortieDebutPlusDuree = clone $sortie->getDateHeureDebut();
            $sortieDebutPlusDuree->modify("+{$sortie->getDuree()} minutes");
            if ($sortie->getDateLimiteInscription() < $ajd && $sortie->getDateHeureDebut() > $ajd  ) {
                $sortie->setEtat($etatcloture);
                $this->entityManager->persist($sortie);
            }
            elseif($sortie->getDateHeureDebut() < $ajd && $sortieDebutPlusDuree > $ajd &&  $sortie->getDateHeureDebut() > $unMoisAuparavant  ) {
                $sortie->setEtat($etatencourt);
                $this->entityManager->persist($sortie);
            }
            elseif($sortie->getDateHeureDebut() < $unMoisAuparavant  ) {
                $sortie->setEtat($etatarchiver);
                $this->entityManager->persist($sortie);
            }
        }

        $this->entityManager->flush();
        return $this->render('test.html.twig', [
            'sortie' => "test",
        ]);
    }

}
