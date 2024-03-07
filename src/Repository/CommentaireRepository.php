<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 *
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

//    /**
//     * @return Commentaire[] Returns an array of Commentaire objects
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

//    public function findOneBySomeField($value): ?Commentaire
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
  /**
     * @param int $publicationId
     * @return Commentaire[]
     */
    public function findByPublicationId($publicationId)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.publication = :publicationId')
            ->setParameter('publicationId', $publicationId)
            ->getQuery()
            ->getResult();
    }
    // public function findBySearchTerm($searchTerm)
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.user.id = :searchTerm OR c.publication.id = :searchTerm OR c.contenu_com LIKE :content')
    //         ->setParameter('searchTerm', $searchTerm)
    //         ->setParameter('content', '%'.$searchTerm.'%')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
}
