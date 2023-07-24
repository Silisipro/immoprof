<?php

namespace App\Repository;

use App\Entity\Bien;
use App\Entity\BienRecherche;
use App\Entity\TypeBien;
use App\Entity\User;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /*
   * @return Bien[] Returns an array of Bien objects
   */
    public function findVisible(BienRecherche $recherche): array
    {
        $query = $this->createQueryBuilder('b')
            ->andWhere('b.sold = 0')
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
    /*
    * @return Bien[] Returns an array of Bien objects
    */

    public function findLast(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.sold = 0')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
            ;
    }

//    public function BiensLoueVendu(string $etat)
//    {
//        $entityManager=$this->getEntityManager();
//        $qb= $entityManager->createQuery(
//            '
//           SELECT b
//           FROM App\Entity\Bien b
//           WHERE b.etat = :etat
//           AND b.deleted = 0
//           ORDER BY b.createdAt DESC
//          '
//        )
//            ->setParameter('etat', $etat);
//        return $qb->getResult();
//    }

    /*
    * @return Bien[] Returns an array of Bien objects
    */

    public function biensPubliesParToutuser(string $publie) : array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.etat = :publie')
            ->addOrderBy('b.createdAt', 'DESC')
            ->setParameter('publie', $publie)
            ->getQuery()
            ->getResult();
    }

//    public function vendLoue(string $etat, string $etat2): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.etat = :etat1')
//            ->orWhere('b.etat = :etat2')
//            ->addOrderBy('b.createdAt', 'DESC')
//            ->setParameter('etat1',$etat)
//            ->setParameter('etat2',$etat2)
//            ->getQuery()
//            ->getResult()
//            ;
//    }

//    public function vendLoueParUser(string $etat, string $etat2, User $user): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.etat = :etat')
//            ->orWhere('b.etat = :etat2')
//            ->andWhere('b.user = :user')
//            ->addOrderBy('b.createdAt', 'DESC')
//            ->setParameter('etat',$etat)
//            ->setParameter('etat2',$etat2)
//            ->setParameter('user',$user)
//            ->getQuery()
//            ->getResult()
//            ;
//    }

//    public function recupererBiensParCategories(string $categorie): array
//    {
//        $entityManager=$this->getEntityManager();
//        $qb= $entityManager->createQuery(
//            '
//           SELECT b,t
//           FROM App\Entity\Bien b
//           JOIN b.typeBien t
//           WHERE t.categorie = :categorie
//           AND b.deleted = 0
//           ORDER BY b.createdAt DESC
//
//          '
//        )
//            ->setParameter('categorie', $categorie);
//
//        return $qb->getResult();
//    }
//    public function recupererBiensParTypeBiens(string $typeBien): array
//    {
//        $entityManager=$this->getEntityManager();
//        $qb= $entityManager->createQuery(
//            '
//           SELECT b,t
//           FROM App\Entity\Bien b
//           JOIN b.typeBien t
//           WHERE t.id = :type
//           AND b.deleted = 0
//           ORDER BY b.createdAt DESC
//          '
//        )
//            ->setParameter('type', $typeBien);
//
//        return $qb->getResult();
//    }

    public function rechercherBien(BienRecherche $rechercher): array
    {
        $query = $this->createQueryBuilder('b')
            ->andWhere('b.deleted = 0')
            ->addOrderBy('b.createdAt', 'DESC');


        if($rechercher->getMaxPrice()){
            $query=$query
                ->andWhere('b.price <= :maxprice')
                ->setParameter('maxprice', $rechercher->getMaxPrice());

        }
        if($rechercher->getMinSurface()){
            $query=$query
                ->andWhere('b.surface >= :minsurface')
                ->setParameter('minsurface', $rechercher->getMinSurface());
        }
        if($rechercher->getLieu()){
            $query=$query
                ->andWhere('b.city >= :city')
                ->setParameter('city', $rechercher->getLieu());
        }

        if ($rechercher->getTypeBien()) {

            $query = $query
                ->join('b.typeBien', 't')
                ->andWhere( 't.id IN (:typeBien)')
                ->setParameter('typeBien', $rechercher ->getTypeBien());

        }

        if ($rechercher->getStanding()) {

            $query = $query
                ->join('b.standing', 's')
                ->andWhere( 's.id IN (:standing)')
                ->setParameter('standing', $rechercher ->getStanding());

        }
        return $query
            ->getQuery()
            ->getResult();

    }

    public function rechercherBienv(BienRecherche $rechercherv): array
    {
        $query = $this->createQueryBuilder('b')
            ->andWhere('b.deleted = 0')
            ->addOrderBy('b.createdAt', 'DESC');


        if($rechercherv->getMaxPrice()){
            $query=$query
                ->andWhere('b.price <= :maxprice')
                ->setParameter('maxprice', $rechercherv->getMaxPrice());

        }
        if($rechercherv->getMinSurface()){
            $query=$query
                ->andWhere('b.surface >= :minsurface')
                ->setParameter('minsurface', $rechercherv->getMinSurface());
        }
        if($rechercherv->getLieu()){
            $query=$query
                ->andWhere('b.city >= :city')
                ->setParameter('city', $rechercherv->getLieu());
        }

        if ($rechercherv->getTypeBien()) {

            $query = $query
                ->join('b.typeBien', 't')
                ->andWhere( 't.id IN (:typeBien)')
                ->setParameter('typeBien', $rechercherv ->getTypeBien());

        }
        return $query
            ->getQuery()
            ->getResult();

    }


    public function recupererBiensFavoris(): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.typeBien', 't')
            ->addSelect('t')
            ->andWhere('b.deleted = :deleted')
            ->setParameter('deleted', false)
            ->andWhere('b.sold = :sold')
            ->setParameter('sold', true)
            ->andWhere('b.etat = :etat')
            ->setParameter('etat', 'publie')
            ->orderBy('t.categorie', 'ASC')
            ->addOrderBy('b.name', 'ASC')
            ->addOrderBy('b.lieu', 'ASC')
            ->addOrderBy('b.price', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function recupererBiensParCategorie(string $categorieTypeBien, ?array $tabFiltre = null): Query
    {
        $query = $this->createQueryBuilder('b')
            ->innerJoin('b.typeBien', 't')
            ->addSelect('t')
            ->andWhere('b.deleted = :deleted')
            ->setParameter('deleted', false)
            ->andWhere('b.etat = :etat')
            ->setParameter('etat', 'publie')
            ->andWhere('t.categorie = :categorie')
            ->setParameter('categorie', $categorieTypeBien)
        ;

        if (is_array($tabFiltre)) {
            if (!is_null($tabFiltre['typeBien'])) {
                $query
                    ->andWhere('t = :typeBien')
                    ->setParameter('typeBien', $tabFiltre['typeBien']->getId())
                ;
            }

            if (!is_null($tabFiltre['standing'])) {
                $query
                    ->andWhere('b.standing = :standing')
                    ->setParameter('standing', $tabFiltre['standing']->getId())
                ;
            }

            if (!is_null($tabFiltre['lieu'])) {
                $query
                    ->andWhere('LOWER(b.lieu) LIKE :lieu')
                    ->setParameter('lieu', '%'. strtolower($tabFiltre['lieu']). '%')
                ;
            }

            if (!is_null($tabFiltre['price'])) {
                $query
                    ->andWhere('b.price BETWEEN :loyerBudgetMin AND :loyerBudgetMax')
                    ->setParameter('loyerBudgetMin', ((int)$tabFiltre['price'] - 25000))
                    ->setParameter('loyerBudgetMax', ((int)$tabFiltre['price'] + 25000))
                ;
            }

        } // Fin is_array sur $tabFiltre

        return $query
            ->addOrderBy('b.createdAt', 'ASC')
            ->getQuery();
    }

    public function recupererBiensParTypeBien(TypeBien $typeBien): Query
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.typeBien', 't')
            ->addSelect('t')
            ->andWhere('b.deleted = :deleted')
            ->setParameter('deleted', false)
            ->andWhere('b.etat = :etat')
            ->setParameter('etat', 'publie')
            ->andWhere('t = :typeBien')
            ->setParameter('typeBien', $typeBien->getId())
            ->addOrderBy('b.createdAt', 'ASC')
            ->getQuery()
            ;
    }

    public function recupererBiensLoueVendu(object $user): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.typeBien', 't')
            ->addSelect('t')
            ->andWhere('b.deleted = :deleted')
            ->setParameter('deleted', false)
            ->andWhere('b.etat = :etat')
            ->setParameter('etat', 'loue')
            ->orWhere('b.etat = :etat2')
            ->setParameter('etat2', 'vendu')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user->getId())
            ->addOrderBy('b.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function recupererBiensLoueVenduParAgent(): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.typeBien', 't')
            ->addSelect('t')
            ->innerJoin('b.user', 'u')
            ->addSelect('u')
            ->andWhere('b.deleted = :deleted')
            ->setParameter('deleted', false)
            ->andWhere('b.etat = :etat')
            ->setParameter('etat', 'loue')
            ->orWhere('b.etat = :etat2')
            ->setParameter('etat2', 'vendu')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%'.'["ROLE_AGENT"]'.'%')
            ->addOrderBy('b.b.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function recupererBiensPubliesPourAgent(): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.typeBien', 't')
            ->addSelect('t')
            ->innerJoin('b.user', 'u')
            ->addSelect('u')
            ->andWhere('b.deleted = :deleted')
            ->setParameter('deleted', false)
            ->andWhere('b.etat = :etat')
            ->setParameter('etat', 'publie')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%'.'["ROLE_AGENT"]'.'%')
            ->addOrderBy('b.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function recupererBiensTotalALouerPourAnnee(string $annee)
    {
        return $this->createQueryBuilder('b')
            ->select("COUNT(b)")
            ->innerJoin('b.typeBien', 't')
            ->andWhere('t.categorie = :categorie')
            ->setParameter('categorie', 'a_louer')
            ->andWhere('b.createdAt BETWEEN :dateDebut AND :dateFin')
            ->setParameter('dateDebut', new \DateTime("$annee-01-01 00:00:00"))
            ->setParameter('dateFin', new \DateTime("$annee-12-31 23:59:59"))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function recupererBiensTotalAVendrePourAnnee(string $annee)
    {
        return $this->createQueryBuilder('b')
            ->select("COUNT(b)")
            ->innerJoin('b.typeBien', 't')
            ->andWhere('t.categorie = :categorie')
            ->setParameter('categorie', 'a_vendre')
            ->andWhere('b.createdAt BETWEEN :dateDebut AND :dateFin')
            ->setParameter('dateDebut', new \DateTime("$annee-01-01 00:00:00"))
            ->setParameter('dateFin', new \DateTime("$annee-12-31 23:59:59"))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}