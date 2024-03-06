<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\Expr;

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

    public function getThemeCounts(): array
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addScalarResult('themeE', 'themeE');
        $rsm->addScalarResult('eventCount', 'eventCount');

        $sql = 'SELECT theme_e AS themeE, COUNT(id) AS eventCount FROM event GROUP BY themeE';
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    public function getParticipationCountByTheme()
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e.theme_e as theme, COUNT(p.id) as participationCount')
            ->leftJoin('e.participations', 'p')
            ->groupBy('e.theme_e');

        return $qb->getQuery()->getResult();
    }

    public function getParticipationCounts()
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.participations', 'p')
            ->select('e.id', 'e.nomE', 'COUNT(p.id) as participationCount')
            ->groupBy('e.id')
            ->getQuery()
            ->getResult();
    }

}
