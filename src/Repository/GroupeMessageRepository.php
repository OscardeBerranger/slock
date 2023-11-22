<?php

namespace App\Repository;

use App\Entity\GroupeMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupeMessage>
 *
 * @method GroupeMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeMessage[]    findAll()
 * @method GroupeMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeMessage::class);
    }

//    /**
//     * @return GroupeMessage[] Returns an array of GroupeMessage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GroupeMessage
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
