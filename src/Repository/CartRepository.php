<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
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
            ->andWhere('c.user = :user')
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
        $this->save($cart);
        return $cart;
    }

    public function save(Cart $cart): void {
        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();
       } 
       
}
