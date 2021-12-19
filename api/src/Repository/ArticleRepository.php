<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

     /**
      * @return Article[] Returns an array of Article objects
      */
    public function findArticles(string $searchName, array $tags = [])
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.name LIKE :searchName')
            ->setParameter('searchName', $searchName . '%');

        foreach ($tags as $i => $tag) {
            $qb->innerJoin('a.tags', 't' . $i)
                ->andWhere('t' . $i . '.title = :title' . $i)
                ->setParameter('title' . $i, $tag);
        }

        return $qb
        ->orderBy('a.id', 'ASC')
        ->setMaxResults(25)
        ->getQuery()
        ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
