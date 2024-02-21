<?php

namespace App\Repository;

use App\Entity\Condidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Condidature>
 *
 * @method Condidature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Condidature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Condidature[]    findAll()
 * @method Condidature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CondidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Condidature::class);
    }

    public function save(Condidature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if($flush){
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Condidature[] Returns an array of Condidature objects
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

//    public function findOneBySomeField($value): ?Condidature
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
