<?php

namespace App\Repository;

use App\Entity\HistoriqueCourrier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoriqueCourrier>
 *
 * @method HistoriqueCourrier|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueCourrier|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueCourrier[]    findAll()
 * @method HistoriqueCourrier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueCourrierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueCourrier::class);
    }

    public function remove(HistoriqueCourrier $historiqueCourrier, bool $flush = true): void
{
    $this->getEntityManager()->remove($historiqueCourrier);
    if ($flush) {
        $this->getEntityManager()->flush();
    }
}


//    /**
//     * @return HistoriqueCourrier[] Returns an array of HistoriqueCourrier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HistoriqueCourrier
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
