<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function save(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findStudentByNsc($Nom_event){
        return $this->createQueryBuilder("s")
           ->where('s.Nom_event LIKE :Nom_event')
           ->setParameter('Nom_event', '%'.$Nom_event.'%')
           ->getQuery()
           ->getResult();
        }

   /**
    * @return Evenement[] Returns an array of Evenement objects
    */

   public function findByNom(Evenement $event): array
   {      
    $qb = $this->createQueryBuilder('p');

    if ($event->getNomEvent()) {
        $qb->andWhere('p.Nom_event LIKE :Nom_event')
            ->setParameter('Nom_event', '%'.$event->getNomEvent().'%');
    }

    if ($event->getLocalisation()) {
        $qb->andWhere('p.localisation LIKE :localisation')
            ->setParameter('localisation', '%'.$event->getLocalisation().'%');
    }

    if ($event->getCategorie()) {
        $qb->andWhere('p.categorie = :categorie')
            ->setParameter('categorie', $event->getCategorie());
    }

    return $qb->getQuery()->getResult();
}

public function getEventOrdredByName()
{
    $qb =  $this->createQueryBuilder('x')
        ->orderBy('x.Nom_event', 'ASC');
    return $qb->getQuery()
        ->getResult();
}

public function getEventOrdredByName2()
{
    $qb =  $this->createQueryBuilder('x')
        ->orderBy('x.Nom_event', 'DESC');
    return $qb->getQuery()
        ->getResult();
}

    }
    

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

