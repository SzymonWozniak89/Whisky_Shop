<?php
namespace App\Service;

use App\Repository\CartRepository;
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

class PaymentService{
    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        public readonly ShipmentRepository $shipmentRepository,
        public readonly PaymentRepository $paymentRepository,
        private readonly Security $security,
        )
    {

    }

    public function getPayment()
    {
        return $this->paymentRepository->findAll();
    }

    public function add(int $id)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $cart = $this->cartRepository->findCartByUserId($user);
        $shipment = $this->shipmentRepository->find($id);
        return $this->cartRepository->setShippingMethod($shipment, $cart);
    }
    
}