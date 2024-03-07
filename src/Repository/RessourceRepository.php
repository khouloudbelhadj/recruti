<?php

namespace App\Repository;

use App\Entity\Ressource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ressource>
 *
 * @method Ressource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ressource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ressource[]    findAll()
 * @method Ressource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ressource::class);
    }

//    /**
//     * @return Ressource[] Returns an array of Ressource objects
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

//    public function findOneBySomeField($value): ?Ressource
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findBySearchTermAndCriteria(string $searchTerm, string $criteria): array
    {
        $qb = $this->createQueryBuilder('r');

        if ($criteria === 'titre_b') {
            $qb->where('r.titre_b LIKE :searchTerm');
        } elseif ($criteria === 'type_b') {
            $qb->where('r.type_b LIKE :searchTerm');
        } else {
            throw new \InvalidArgumentException('Invalid search criteria.');
        }

        return $qb
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
    public function findByCombinedSearch(string $titreSearch, string $typeSearch): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.titre_b LIKE :titreSearch')
            ->andWhere('r.type_b LIKE :typeSearch')
            ->setParameter('titreSearch', '%' . $titreSearch . '%')
            ->setParameter('typeSearch', '%' . $typeSearch . '%')
            ->getQuery()
            ->getResult();
    }    

}