<?php

namespace App\Repository;

use App\Entity\Bien;
use App\Entity\BienRecherche;
use App\Entity\User;
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
            ->andWhere('b.sold = true')
            ->addOrderBy('b.createdAt', 'DESC');


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
    
        
      if ($recherche->getTypeBien()) {
        
        $query = $query 
                 ->join('b.typeBien', 't')
                ->andWhere( 't.id IN (:typeBien)')
                ->setParameter('typeBien', $recherche ->getTypeBien());     

      }
      return $query
      ->getQuery()
      ->getResult();

    } 
    /**
    * @return Bien[] Returns an array of Bien objects
    */

    public function findLast(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.sold = true')
           ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function BiensLoueVendu(string $etat,)
    {
      $entityManager=$this->getEntityManager();
      $qb= $entityManager->createQuery(
          '
           SELECT b
           FROM App\Entity\Bien b
           WHERE b.etat = :etat 
           AND b.deleted = 0
           ORDER BY b.createdAt DESC
          '
       )
        ->setParameter('etat', $etat);

       
            return $qb->getResult();
    }
    
 /**
    * @return Bien[] Returns an array of Bien objects
    */

    public function biensPubliesParToutuser(string $publie) : array

    {
        return $this->createQueryBuilder('b')
         ->andWhere('b.etat = :publie')        
        ->addOrderBy('b.createdAt', 'DESC')
        ->setParameter('publie', $publie)
        ->getQuery()
        ->getResult()

      ;
     }


   // public function biensLoueVenduParUser(string $etat, User $user)

    //{
    //   $entityManager=$this->getEntityManager();
    //    $qb= $entityManager->createQuery(
    //        '
    //            SELECT b
    //            FROM App\Entity\Bien b
    //            WHERE b.etat = :etat 
    //            AND b.deleted = 0
    //            AND b.user = :user
    //            ORDER BY b.createdAt DESC
    //            '
    //   )
    //    ->setParameter('etat', $etat)
    //    ->setParameter('user',$user);

    //   
    //       return $qb->getResult();
    //}


    public function vendLoue(string $etat, string $etat2): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.etat = :etat1')
            ->orWhere('b.etat = :etat2')
            ->addOrderBy('b.createdAt', 'DESC')
            ->setParameter('etat1',$etat)        
            ->setParameter('etat2',$etat2)        
            ->getQuery()
            ->getResult()
        ;
    }

    public function vendLoueParUser(string $etat, string $etat2, User $user): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.etat = :etat')
            ->orWhere('b.etat = :etat2')
            ->andWhere('b.user = :user')
            ->addOrderBy('b.createdAt', 'DESC')
            ->setParameter('etat',$etat)        
            ->setParameter('etat2',$etat2)        
            ->setParameter('user',$user)        
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
