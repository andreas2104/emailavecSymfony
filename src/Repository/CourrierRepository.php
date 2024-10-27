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
        return $this->createQueryBuilder('c')
            ->where('c.expediteur = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    } 
    
 
    public function searchCourriers($searchTerm)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.expediteur', 'e')
            ->leftJoin('c.destinataire', 'd')
            ->addSelect('e', 'd');
    
     
        $qb->where('c.objet LIKE :searchTerm')
           ->setParameter('searchTerm', '%' . $searchTerm . '%');
    
    
        $qb->orWhere('e.email LIKE :searchTerm');
    

        $qb->orWhere('d.email LIKE :searchTerm');
    
      
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $searchTerm)) {
        
            $date = \DateTime::createFromFormat('d/m/Y', $searchTerm);
            if ($date) {
                $qb->orWhere('c.date_envoi = :searchDate')
                   ->setParameter('searchDate', $date->format('Y-m-d'));
            }
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $searchTerm)) {
          
            $date = new \DateTime($searchTerm);
            $qb->orWhere('c.date_envoi = :searchDate')
               ->setParameter('searchDate', $date->format('Y-m-d'));
        }
    
        return $qb->getQuery()->getResult();
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
