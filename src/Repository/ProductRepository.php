<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Product>
 *
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

    public function getPaginated(int $page)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.stock > 0')
            ->orderBy('p.id', 'DESC');

        return (new Paginator($qb))->paginate($page);
    }

    public function get(int $prodId)
    {
        return $this->createQueryBuilder('p')
        ->where('p.id = :id')
        ->setParameter('id', $prodId)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}
