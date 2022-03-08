<?php

namespace App\Repository;

use App\DTO\ProductSearch;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findWithSearch(ProductSearch $criteria): array
    {
        $builder = $this
            ->createQueryBuilder('product');

        if(null !== $criteria->name && !empty($criteria->name))
        {
            $builder = $builder
            ->andWhere('product.name LIKE :name')
            ->setParameter('name', "%{$criteria->name}%");
        }

        if(null !== $criteria->designer)
        {
            $builder = $builder
            ->leftJoin('product.designer', 'designer')
            ->andWhere('designer.id = :designer')
            ->setParameter('designer', $criteria->designer);
        }

        if(null !== $criteria->category)
        {
            $builder = $builder
            ->leftJoin('product.category', 'category')
            ->andWhere('category.id = :category')
            ->setParameter('category', $criteria->category);
        }

        return $builder
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
