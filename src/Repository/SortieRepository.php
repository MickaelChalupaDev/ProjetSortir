<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findSortiesByFilters(
        ?string             $campus,
       ? string             $nom,
       ? DateTimeInterface $dateHeureDebut,
       ? DateTimeInterface $dateLimiteInscription,
       ? User               $organisateur,

    ): array
    {
        $qb = $this->createQueryBuilder('s');

        // Filtres obligatoires
       if ($campus) {
           $qb->andWhere('s.campus = :campus')

                ->setParameter('campus', $campus);
        }


        if ($nom) {
            $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', '%' . $nom . '%');
        }

        if ($dateHeureDebut) {
            $qb->andWhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $dateHeureDebut);
        }

        if ($dateLimiteInscription) {
            $qb->andWhere('s.dateHeureDebut <= :dateFin')
                ->setParameter('dateFin', $dateLimiteInscription);
        }

        if ($organisateur) {
            $qb->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $organisateur);
        }
        return $qb->getQuery()->getResult() ?: [];
    }
}
