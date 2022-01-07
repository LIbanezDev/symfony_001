<?php

namespace App\Repository;

use App\Entity\Clinic;
use App\Entity\Pet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pet[]    findAll()
 * @method Pet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pet::class);
    }

    // /**
    //  * @return Pet[] Returns an array of Pet objects
    //  */

    public function findPaginated($page, $order)
    {
        $perPage = 4;
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.clinic', 'clinic')
            ->leftJoin('p.owner', 'owner')
            ->orderBy('p.'.$order, 'ASC');
        return $query->getQuery()
            ->setMaxResults($perPage)
            ->setFirstResult(($page * $perPage) - $perPage)
            ->getResult();
    }

    public function getCount() {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /*
    public function findOneBySomeField($value): ?Pet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
