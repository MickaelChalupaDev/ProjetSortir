<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

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
       ? Campus             $campus,
       ? string             $nom,
       ? DateTimeInterface  $entre,
       ? DateTimeInterface  $et,
       ? User               $organisateur,
       ? User               $participant,
       ? bool               $val,
       ? Etat               $etat,

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

        if ($entre) {
            $qb->andWhere('s.dateHeureDebut >= :entre')
                ->setParameter('entre', $entre);
        }

        if ($et) {
            $qb->andWhere('s.dateHeureDebut <= :et')
                ->setParameter('et', $et);
        }

        if ($organisateur->getId() !== null) {
            $qb->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $organisateur);
        }
        if (($participant->getId() !== null) and ($val === true)) {
            $qb->leftJoin('s.users', 'users');
            $qb->andWhere('users.id = :participant')
                ->setParameter('participant', $participant->getId());
        }
    $expr=$this->getEntityManager()->getExpressionBuilder();
        if (($participant->getId() !== null) and ($val === false)) {


            $qb->leftJoin('s.users', 'users');
            $qb->andWhere($expr->notIn('users.id',':participant'))
                ->setParameter('participant', $participant->getId());

        }
        if ($etat->getId() !== null) {
            $qb->andWhere('s.etat = :etat')
                ->setParameter('etat', $etat);
        }
        return $qb->getQuery()->getResult() ?: [];
    }
}
