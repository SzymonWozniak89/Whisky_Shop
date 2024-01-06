<?php
namespace App\Service;

use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Shipment;
use App\Entity\User;
use App\Repository\ShipmentRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ShipmentService{
    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        public readonly ShipmentRepository $shipmentRepository,
        private readonly Security $security,
        )
    {

    }

    public function getCheapestShipping()
    {
        return $this->shipmentRepository->getCheapestShipping()->getPrice();
    }

    public function getShipping()
    {
        // order by price ascending
        return $this->shipmentRepository->findBy(array(), array('price' => 'ASC'));
    }

    public function getById($id): Shipment
    {
        return $this->shipmentRepository->find($id);
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