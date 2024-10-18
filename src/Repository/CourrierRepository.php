<?php

namespace App\Repository;

use App\Entity\Courrier;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courrier>
 *
 * @method Courrier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Courrier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Courrier[]    findAll()
 * @method Courrier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourrierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courrier::class);
    }

    public function findCourriersEnvoyes(User $user): array
    {
        return $this->createQueryBuilder("c")
        ->where("c.expediteur = :userId")
        ->setParameter('userId', $user->getId())
        ->getQuery()
        ->getResult();
    }
    //on recupere les courrier concue

    public function findCourriersRecus(User $user): array
    {
        return $this->createQueryBuilder('c')
        ->innerJoin('c.destinataire', 'd')
        ->where('d.id = :userId')
        ->setParameter('userId', $user->getId())
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return Courrier[] Returns an array of Courrier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Courrier
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
