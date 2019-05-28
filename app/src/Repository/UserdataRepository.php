<?php

namespace App\Repository;

use App\Entity\Userdata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Userdata|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userdata|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userdata[]    findAll()
 * @method Userdata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Userdata[]    findByUser($value)
 */
class UserdataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Userdata::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Userdata $userdata Userdata entity
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Userdata $userdata): void
    {

        $this->_em->persist($userdata);
        $this->_em->flush($userdata);
    }
    // /**
    //  * @return Userdata[] Returns an array of Userdata objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Userdata
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
