<?php

namespace App\Repository;

use App\Entity\Don;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Don>
 *
 * @method Don|null find($id, $lockMode = null, $lockVersion = null)
 * @method Don|null findOneBy(array $criteria, array $orderBy = null)
 * @method Don[]    findAll()
 * @method Don[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Don::class);
    }

    public function save(Don $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Don $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Don[] Returns an array of Don objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Don
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function searchNom($Nom_Don)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.NameD LIKE :ncl')
            ->setParameter('ncl', '%' . $Nom_Don . '%')
            ->getQuery()
            ->execute();
    }
    public function searchLocalisation($Localisation_Don)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.Localisation LIKE :ncl')
            ->setParameter('ncl', '%' . $Localisation_Don . '%')
            ->getQuery()
            ->execute();
    }
    public function orderByName()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.NameD', 'ASC')
            ->getQuery()->getResult();
    }
    public function orderQuantite()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.quantite', 'ASC')
            ->getQuery()->getResult();
    }
}
