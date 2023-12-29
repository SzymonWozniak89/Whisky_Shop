<?php
namespace App\Service;

use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class CartService{
    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        private readonly Security $security,
        )
    {

    }

    public function add(int $prodId)
    {
        /** @var User $user */
        $user = $this->security->getUser(); 
        $cart = $this->cartRepository->findCartByUserId($user);
        if ($cart == null)
        {
            $cart = $this->cartRepository->new($user);
        }

        $product = $this->productRepository->get($prodId);

        $cartItem = $this->cartItemRepository->findExistCartItem($product, $cart);

        if ($cartItem == null) {
            $cart->add($product);
        } else {
            $cartItemQuantity = $cartItem->getQuantity();
            $cartItem->setQuantity($cartItemQuantity + 1);
        }
    
        $this->cartRepository->save($cart);
        return $cart;
    }

    public function remove($cartItem)
    {
       return $this->cartItemRepository->remove($cartItem);
    }

    public function getNumberOfProductsInCart()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->getNumberOfProductsInCart($user);
    }

    public function getTotalPrice()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->getTotalPrice($user);
    }

    public function getCartItems()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartItemRepository->get($user);
    }

    public function getSubtotalPrice()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->getSubtotalPrice($user);
    }

    public function getShippingPrice()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->getShippingPrice($user);
    }


    public function getItemQuantity(int $prodId)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->getItemQuantity($user, $prodId);
    }

    public function getItemTotalPrice(int $prodId)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->getItemTotalPrice($user, $prodId);
    }

    public function subQuantity(int $prodId)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->subQuantity($user, $prodId);
    }
}