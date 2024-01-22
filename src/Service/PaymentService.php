<?php
namespace App\Service;

use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\CartItemRepository;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\PaymentRepository;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Shipment;
use App\Entity\User;
use App\Repository\ShipmentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Order;

class PaymentService{
    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        public readonly ShipmentRepository $shipmentRepository,
        public readonly PaymentRepository $paymentRepository,
        public readonly OrderRepository $orderRepository,
        private readonly Security $security,
        )
    {

    }

    public function getPayment()
    {
        return $this->paymentRepository->findAll();
    }

    public function complete(Order $order)
    {
        $order->setStatus(Order::STATUS_COMPLETED);
        $this->orderRepository->save($order);
    }
    
}