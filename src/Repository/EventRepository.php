<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Recherche les événements en fonction du terme de recherche.
     *
     * @param string $searchTerm Le terme de recherche
     *
     * @return Event[] Liste des événements correspondant au terme de recherche
     */
    public function findBySearchTermAndCriteria(string $searchTerm, string $criteria): array
    {
        $qb = $this->createQueryBuilder('e');

        if ($criteria === 'nom_e') {
            $qb->where('e.nom_e LIKE :searchTerm');
        } elseif ($criteria === 'theme_e') {
            $qb->where('e.theme_e LIKE :searchTerm');
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
            ->where('e.nom_e LIKE :nameSearch')
            ->andWhere('e.theme_e LIKE :themeSearch')
            ->setParameter('nameSearch', '%' . $nameSearch . '%')
            ->setParameter('themeSearch', '%' . $themeSearch . '%')
            ->getQuery()
            ->getResult();
    }
}
