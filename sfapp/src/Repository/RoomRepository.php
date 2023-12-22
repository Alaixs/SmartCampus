<?php

namespace App\Repository;

use App\Entity\Room;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @extends ServiceEntityRepository<Room>
 *
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

//    /**
//     * @return Room[] Returns an array of Room objects
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

//    public function findOneBySomeField($value): ?Room
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findBySearch(SearchData $searchData): array
    {
        $data = $this->createQueryBuilder('r');
        if(!empty($searchData->q))
        {
            $data = $data
                ->andWhere('r.name LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }
        if(!empty($searchData->floors))
        {
            $data = $data
                ->andWhere('r.floor IN (:floors)')
                ->setParameter('floors', $searchData->floors);
        }
        if(!empty($searchData->aquisitionUnitState))
        {
            $data = $data
                ->join('r.SA', 's')
                ->andWhere('s.state IN (:aquisitionUnitState)')
                ->setParameter('aquisitionUnitState', $searchData->aquisitionUnitState);
        }

        $data = $data
            ->getQuery()
            ->getResult();
        return $data;
    }
}
