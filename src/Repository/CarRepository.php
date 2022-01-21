<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function searchByTerm($term)
    {
        // QueryBuilder permet de créer des requêtes SQL en PHP
        $queryBuilder = $this->createQueryBuilder('car');

        $query = $queryBuilder
            ->select('car') // select sur la table car
            ->leftJoin('car.brand', 'brand') // leftjoin sur la table brand
            ->leftJoin('car.groupe', 'groupe') // leftjoin sur la table groupe
            ->where('car.name LIKE :term') // WHERE de SQL
            ->orWhere('car.year LIKE :term') // OR WHERE de SQL
            ->orWhere('car.engine LIKE :term') // OR WHERE de SQL
            ->orWhere('brand.name LIKE :term')
            ->orWhere('brand.country LIKE :term')
            ->orWhere('groupe.name LIKE :term')
            ->orWhere('groupe.country LIKE :term')
            ->setParameter('term', '%' . $term . '%') // On attribue le term rentré et on le sécurise
            ->getQuery();

        return $query->getResult();
    }



    // /**
    //  * @return Car[] Returns an array of Car objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Car
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
