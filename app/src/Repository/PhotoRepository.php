<?php

namespace App\Repository;

use App\Entity\Photo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
/**
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Photo[]    findByUser($value)
 * @method Photo[]    findByTags($value)
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()//join likes
            /* SELECT photo.description, COUNT(likerate.id) AS likes FROM photo inner join likerate ON (likerate.photo_id = photo.id) Group by likerate.photo_id; */
//            ->innerJoin('p.likerates', 'l')
//            ->addSelect('p')
//            ->addSelect('COUNT(l.id) AS likes')
//            ->addGroupBy('l.photo')
            ->orderBy('p.publication_date', 'DESC')
//            ->where('l.photo= p.id')
    ;}

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('p');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Photo $photo Photo entity
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Photo $photo): void
    {
        $this->_em->persist($photo);
        $this->_em->flush($photo);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Photo $photo Photo entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Photo $photo): void
    {
        $this->_em->remove($photo);
        $this->_em->flush($photo);
    }

    /**
    * @return Photo[] Returns an array of Photo objects
    */

    public function findByTag($tag)
    {
        return $this->queryAll()
            ->innerJoin('p.tags', 't')
            ->where('t = :val')
            ->setParameter('val', $tag);

//            ->addSelect('p')
//            ->select('p.id', 'p.description', 'p.description', 'p.camera_specyfication');
    }


    // /**
    //  * @return Photo[] Returns an array of Photo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Photo
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
