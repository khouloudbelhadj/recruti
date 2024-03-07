<?php

namespace App\Repository;

use App\Entity\Biblio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Biblio>
 *
 * @method Biblio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Biblio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Biblio[]    findAll()
 * @method Biblio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiblioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biblio::class);
    }

    /**
     * Recherche les biblios en fonction du terme de recherche.
     *
     * @param string $searchTerm Le terme de recherche
     *
     * @return Event[] Liste des biblios correspondant au terme de recherche
     */

    public function findBySearchTermAndCriteria(string $searchTerm, string $criteria): array
    {
        $qb = $this->createQueryBuilder('b');

        if ($criteria === 'nom_b') {
            $qb->where('b.nom_b LIKE :searchTerm');
        } elseif ($criteria === 'domaine_b') {
            $qb->where('b.domaine_b LIKE :searchTerm');
        } else {
            throw new \InvalidArgumentException('Invalid search criteria.');
        }

        return $qb
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
    public function findByCombinedSearch(string $nameSearch, string $themeSearch): array
    {
        return $this->createQueryBuilder('e')
            ->where('b.nom_b LIKE :nameSearch')
            ->andWhere('b.domaine_b LIKE :fieldSearch')
            ->setParameter('nameSearch', '%' . $nameSearch . '%')
            ->setParameter('fieldSearch', '%' . $fieldSearch . '%')
            ->getQuery()
            ->getResult();
    }    


//    /**
//     * @return Biblio[] Returns an array of Biblio objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Biblio
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
