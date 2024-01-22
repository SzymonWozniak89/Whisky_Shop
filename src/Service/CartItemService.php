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

class CartItemService{
    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        private readonly Security $security,
        )
    {

    }
    public function unsetCart(CartItem $cartItem)
    {
        $cartItem->setCart(null);
        $this->cartItemRepository->save($cartItem);
    }
}