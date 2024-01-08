<?php
namespace App\Service;

use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Repository\OrderRepository;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Entity\Order;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;


class OrderService{

    private User $user;

    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly OrderRepository $orderRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        private readonly Security $security,
        private readonly CartCalculatorService $cartCalculatorService,
        private readonly AddressService $addressService,
        private readonly EntityManagerInterface $entityManager,
        private readonly CartService $cartService,
        )
    {
       $user = $this->security->getUser();
    }


    public function createPending(): Order
    {
        $order = new Order();
        $order->setAmount($this->cartCalculatorService->getOrderTotalPrice());
        //TODO Dodać netto brutto
        $order->setAddress($this->addressService->getShippingAddress());
        $order->setUser($this->user);
        $order->setShipmentPrice($this->cartService->getShippingPrice());

        $this->orderRepository->save($order);
        return $order;
    }


}