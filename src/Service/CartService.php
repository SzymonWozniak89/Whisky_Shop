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

        // @todo zrobić cartItem service -> przerzucić cały burdel dotyczący cartItems do niego
        // todo zrobić widok koszyka

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
}