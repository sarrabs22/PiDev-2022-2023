<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Reclamations[] Returns an array of Reclamations objects
    */

   public function findByNom(Reclamation $reclamation): array
   {      
    $qb = $this->createQueryBuilder('p');

    if ($reclamation->getEtat()) {
        $qb->andWhere('p.etat LIKE :etat')
            ->setParameter('etat', '%'.$reclamation->getEtat().'%');
    }

    if ($reclamation->getMotifDeReclamation()) {
        $qb->andWhere('p.MotifDeReclamation LIKE :MotifDeReclamation')
            ->setParameter('MotifDeReclamation', '%'.$reclamation->getMotifDeReclamation().'%');
    }

    if ($reclamation->getCategorieRec()) {
        $qb->andWhere('p.categorieRec = :categorieRec')
            ->setParameter('categorieRec', $reclamation->getCategorieRec());
    }

    return $qb->getQuery()->getResult();
}

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
