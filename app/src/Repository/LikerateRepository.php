<?php

namespace App\Repository;

use App\Entity\Likerate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Likerate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likerate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likerate[]    findAll()
 * @method Likerate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Likerate[]    countByPhoto($value)
 */
class LikerateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Likerate::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Comment $comment Comment entity
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Likerate $like): void
    {
        $this->_em->persist($like);
        $this->_em->flush($like);
    }
    // /**
    //  * @return Likerate[] Returns an array of Likerate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Likerate
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
