<?php

namespace App\Repository;

use App\Entity\Participaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participaction>
 *
 * @method Participaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participaction[]    findAll()
 * @method Participaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participaction::class);
    }

//    /**
//     * @return Participaction[] Returns an array of Participaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Participaction
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
