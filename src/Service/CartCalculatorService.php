<?php
namespace App\Service;

use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Repository\ShipmentRepository;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class CartCalculatorService{

    private User $user;

    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        private readonly Security $security,
        private readonly ShipmentRepository $shipmentRepository,
        )
    {
        $this->user = $security->getUser();
    }

    public function getCartTotalPrice()
    {
        $itemsPrice = $this->cartRepository->getSubtotalPrice($this->user);
        $shipmentCheapestPrice = $this->shipmentRepository->getCheapestShipping()->getPrice();
        $cartTotalPrice = $itemsPrice + $shipmentCheapestPrice;
        return $cartTotalPrice;
    }

    public function getOrderTotalPrice()
    {
        return $this->cartRepository->getOrderTotalPrice($this->user);
    }

}