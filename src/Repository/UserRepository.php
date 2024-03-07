<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function findUserByEmailAndPassword(string $email, string $password): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email_user = :email')
            ->setParameter('email', $email)
            ->andWhere('u.password = :password')
            ->setParameter('password', $password) // Note: Passwords should be securely hashed in a real-world application
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findBySearchQuery($query, $searchAttribute)
    {
        $queryBuilder = $this->createQueryBuilder('u');
    
        switch ($searchAttribute) {
            case 'username':
                $queryBuilder->andWhere('u.username LIKE :query');
                break;
            case 'email':
                $queryBuilder->andWhere('u.email_user LIKE :query');
                break;
            case 'role':
                $queryBuilder->andWhere('u.role LIKE :query');
                break;
        }
    
        return $queryBuilder
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }


  


    public function findAllSorted(string $attribute, string $order): array
{
    // Define the sorting criteria based on the attribute and order
    $criteria = [$attribute => $order];
    
    // Fetch the sorted list of users using Doctrine's findBy method
    return $this->findBy([], $criteria);
}


public function findDistinctRoles(): array
{
    return $this->createQueryBuilder('u')
        ->select('DISTINCT u.role')
        ->getQuery()
        ->getResult();
}


// UserRepository.php
public function countUsersByRole(): array
{
    $roleCounts = $this->createQueryBuilder('u')
        ->select('u.role, COUNT(u.id) AS userCount')
        ->groupBy('u.role')
        ->getQuery()
        ->getResult();

    $roleData = [];
    foreach ($roleCounts as $roleCount) {
        $roleData[$roleCount['role']] = $roleCount['userCount'];
    }

    return $roleData;
}




    

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
