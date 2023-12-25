<?php

namespace App\Repository;

use App\Entity\CartItem;
use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartItem>
 *
 * @method CartItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartItem[]    findAll()
 * @method CartItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    public function findExistCartItem(Product $product, Cart $cart)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.cart = :cart')
            ->andWhere('i.product = :product')
            ->setParameter('cart', $cart)
            ->setParameter('product', $product)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function get(User $user)
    {
        return $this->createQueryBuilder('i')
            ->select('i','c','p')
            // ->addSelect('(i.quantity)*(p.price) as totalPrice')
            ->leftJoin('i.cart', 'c')
            ->leftJoin('i.product', 'p')
            ->andWhere('c.user = :user')
            ->andWhere('c.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', Cart::STATUS_CREATED)
            ->getQuery()
            ->getResult()
        ;
    }

    public function remove(CartItem $cartItem): void
    {
         $this->getEntityManager()->remove($cartItem);
         $this->getEntityManager()->flush();
  
    }
}
