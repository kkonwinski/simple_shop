<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShoppingCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingCart[]    findAll()
 * @method ShoppingCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingCart::class);
    }

    // /**
    //  * @return ShoppingCart[] Returns an array of ShoppingCart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
//SELECT cat.* FROM category cat
//LEFT JOIN fortune_cookie fc ON fc.categoryId = cat.id
//WHERE fc.fortune LIKE '%cat%';
//public function search($term)
// return $this->createQueryBuilder('cat')
//            ->andWhere('cat.name LIKE :searchTerm
//                OR cat.iconKey LIKE :searchTerm
//                OR fc.fortune LIKE :searchTerm')
//            ->leftJoin('cat.fortuneCookies', 'fc')
//            ->setParameter('searchTerm', '%'.$term.'%')
//            ->getQuery()
//            ->execute();
    public function getProductName()
    {
//    $qb=$this->getEntityManager()->createQueryBuilder();
//    $qb->add('select','product')
//        ->from('App:ShoppingCart','sc')
//        ->join(Product::class,'product','WITH','product.id=sc.product_id');
//    return $qb->getQuery()->getResult();
        $dql = "SELECT 
    sc.*
    FROM shopping_cart sc
        Left JOIN product p On p.id = sc.product_id
       ";

        return $this->getEntityManager()->createNativeQuery($dql)->getResult();
        //return $query;
    }
    /*
    public function findOneBySomeField($value): ?ShoppingCart
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
