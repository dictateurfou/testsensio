<?php

namespace App\Repository;

use App\Entity\AccountValidation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccountValidation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountValidation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountValidation[]    findAll()
 * @method AccountValidation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountValidationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccountValidation::class);
    }

    // /**
    //  * @return AccountValidation[] Returns an array of AccountValidation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccountValidation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
