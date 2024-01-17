<?php
namespace App\Service;

use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Entity\Order;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Func;

class OrderService{

    private User $user;

    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly OrderRepository $orderRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        public readonly PaymentRepository $paymentRepository,
        private readonly Security $security,
        private readonly CartCalculatorService $cartCalculatorService,
        private readonly AddressService $addressService,
        private readonly EntityManagerInterface $entityManager,
        private readonly CartService $cartService,
        private readonly CartItemService $cartItemService,
        )
    {
       $this->user = $security->getUser();
    }


    public function createPending($paymentId): Order
    {
        $orderTotalPrice = $this->cartCalculatorService->getOrderTotalPrice();

        $order = new Order();
        $order->setAmount($orderTotalPrice);
        $order->setNetAmount(round($orderTotalPrice/1.23, 2));
        $order->setVatAmount(round($orderTotalPrice-($orderTotalPrice/1.23), 2));
        $order->setAddress($this->addressService->getShippingAddress());
        $order->setUser($this->user);
        $order->setShipmentPrice($this->cartService->getShippingPrice());
        $order->setStatus(Order::STATUS_PENDING);
        $order->setPayment($this->paymentRepository->find($paymentId));

        $cartItems = $this->cartService->getCartItems();
        foreach ($cartItems as $cartItem)
        {
            $order->addCartItem($cartItem);
        }
        
        $this->orderRepository->save($order);

        return $order;
    }

    public function emptyCart()
    {
        $cartItems = $this->cartService->getCartItems();
        foreach ($cartItems as $cartItem)
        {
            $this->cartItemService->unsetCart($cartItem);
        }
    }

    public function getOrderItems($order)
    {
        return $this->cartItemRepository->getOrderItems($order);
    }


}