<?php

namespace App\Repository;

use App\Entity\Bien;
use App\Entity\BienRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bien>
 *
 * @method Bien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bien[]    findAll()
 * @method Bien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bien::class);
    }

    public function save(Bien $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Bien $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Bien[] Returns an array of Bien objects
    */
    public function findVisible(BienRecherche $recherche): array
    {
        $query = $this->createQueryBuilder('b')
            ->andWhere('b.sold = false');
        if($recherche->getMaxPrice()){
            $query=$query
                   ->andWhere('b.price <= :maxprice')
                   ->setParameter('maxprice', $recherche->getMaxPrice());
                   
        }
        if($recherche->getMinSurface()){
            $query=$query
                   ->andWhere('b.surface >= :minsurface')
                   ->setParameter('minsurface', $recherche->getMinSurface());        
        }
         return $query->getQuery()
                      ->getResult()
        ;
      if ($recherche->getTypeBien()->getId()) {
        
        $query = $query 
                ->andWhere( ':typeBien MEMBER OF b.typeBien')
                ->setParameter('tybebien', $recherche ->getTypeBien()->getId());        

      }


    }
    /**
    * @return Bien[] Returns an array of Bien objects
    */

    public function findLast(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.sold = false')
           ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Bien
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
