<?php

namespace App\Repository;

use Doctrine\ORM\Query;
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
        $queryBuilder = $this->createQueryBuilder('r');

        if (!empty($searchData->q)) {
            $queryBuilder
                ->andWhere('r.name LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        if (!empty($searchData->floors)) {
            $queryBuilder
                ->andWhere('r.floor IN (:floors)')
                ->setParameter('floors', $searchData->floors);
        }

        if (!empty($searchData->acquisitionUnitState)) {
            $queryBuilder
                ->leftJoin('r.acquisitionUnit', 's')
                ->andWhere('s.state IN (:acquisitionUnitState)')
                ->setParameter('acquisitionUnitState', $searchData->acquisitionUnitState);

            if (in_array('En attente d\'affectation', $searchData->acquisitionUnitState)) {
                $queryBuilder->orwhere('r.acquisitionUnit IS NULL');
            }
        }

        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }
    public function findRoomsByFloor(int $floor): Query
    {
        return $this->createQueryBuilder('r')
            ->where('r.floor = :floor')
            ->setParameter('floor', $floor)
            ->orderBy('r.name', 'ASC')
            ->getQuery();
    }

}
