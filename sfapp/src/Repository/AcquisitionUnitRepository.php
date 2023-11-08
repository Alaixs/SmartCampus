<?php

namespace App\Repository;

use App\Entity\AcquisitionUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AcquisitionUnit>
 *
 * @method AcquisitionUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method AcquisitionUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method AcquisitionUnit[]    findAll()
 * @method AcquisitionUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcquisitionUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcquisitionUnit::class);
    }

//    /**
//     * @return AcquisitionUnit[] Returns an array of AcquisitionUnit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AcquisitionUnit
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
