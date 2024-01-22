<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\Shipment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findCartByUserId(User $user)
    {
        return $this->createQueryBuilder('c')
            ->addSelect('c','i')
            ->leftJoin('c.cartItems', 'i')
            ->where('c.user = :user')
            ->andWhere('c.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', Cart::STATUS_CREATED)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function new(User $user): Cart
    {
        $cart = new Cart();
        $cart->setUser($user);
        $cart->setStatus(Cart::STATUS_CREATED);
        $cart->setShipment($this->getEntityManager()->getRepository(Shipment::class)->getCheapestShipping());
        $this->save($cart);
        return $cart;
    }

    public function save(Cart $cart): void {
        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();
       } 
       

    public function getNumberOfProductsInCart(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select('count(i.product)')
            ->leftJoin('c.cartItems', 'i')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getOrderTotalPrice(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select('sum((i.quantity)*(p.price)) + coalesce(s.price, 0)')
            ->leftJoin('c.cartItems', 'i')
            ->leftJoin('i.product','p')
            ->leftJoin('c.shipment','s')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getSubtotalPrice(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select('sum((i.quantity)*(p.price))')
            ->leftJoin('c.cartItems', 'i')
            ->leftJoin('i.product','p')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getShippingPrice(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select('s.price')
            ->leftJoin('c.shipment','s')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getItemQuantity(User $user, int $prodId)
    {
        return $this->createQueryBuilder('c')
            ->select('i.quantity')
            ->leftJoin('c.cartItems', 'i')
            ->where('c.user = :user')
            ->andWhere('i.product = :prodId')
            ->setParameter('user', $user)
            ->setParameter('prodId', $prodId)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getItemTotalPrice(User $user, int $prodId)
    {
        return $this->createQueryBuilder('c')
            ->select('i.quantity * p.price')
            ->leftJoin('c.cartItems', 'i')
            ->leftJoin('i.product', 'p')
            ->where('c.user = :user')
            ->andWhere('i.product = :prodId')
            ->setParameter('user', $user)
            ->setParameter('prodId', $prodId)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function subQuantity(User $user, int $prodId)
    {
        $query = "update cart_item
                join cart on cart.cart_id = cart_item.cart_id
                set cart_item.cartItem_quantity = cart_item.cartItem_quantity - 1
                where cart_item.product_id = ?
                and cart.user_id = ?;";
        $con = $this->getEntityManager()->getConnection();
        $resultSet = $con->executeQuery($query, [
            1 => $prodId, 
            2 => $user->getId(),
        ]);

        return $resultSet->fetchAllAssociative();
    }

    public function setShippingMethod(Shipment $shipment, Cart $cart): void
    {
        $cart->setShipment($shipment);
        $this->save($cart);
    }

}
