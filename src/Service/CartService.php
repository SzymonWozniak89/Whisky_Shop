<?php
namespace App\Service;

use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Service\StockService;
use Symfony\Bundle\SecurityBundle\Security;
use App\Exception\ProductStockDepletedException;

class CartService{
    public function __construct(
        public readonly CartRepository $cartRepository,
        public readonly ProductRepository $productRepository,
        public readonly CartItemRepository $cartItemRepository,
        public readonly StockService $stockService,
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
        try{

            $product = $this->productRepository->get($prodId);
            //$this->stockService->checkStockIsAvalible($product);

            $cartItem = $this->cartItemRepository->findExistCartItem($product, $cart);

            if ($cartItem == null) {
                $cart->add($product);
            } else {
                $cartItemQuantity = $cartItem->getQuantity();
                $cartItem->setQuantity($cartItemQuantity + 1);
            }
        
            $this->cartRepository->save($cart);
            $this->stockService->decrease($product, 1);
            return $cart;
        } catch (ProductStockDepletedException $psd) {
            //$this->addFlash('error', $psd->getMessage());

        }

    }

    public function subQuantity(int $prodId)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $this->cartRepository->subQuantity($user, $prodId);
        $product = $this->productRepository->get($prodId);
        $this->stockService->increase($product, 1);
    }

    public function remove(CartItem $cartItem)
    {
        $cartItemQuantity = $cartItem->getQuantity();
        $product = $this->productRepository->get($cartItem->getProduct()->getId());
        $this->stockService->increase($product, $cartItemQuantity);
        $this->cartItemRepository->remove($cartItem);
    }

    public function getNumberOfProductsInCart()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->getNumberOfProductsInCart($user);
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

    public function getProductStock($prodId)
    {
        return $this->productRepository->get($prodId)->getStock();
    }

    public function findUserCart()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->cartRepository->findOneBy(['user' => $user]);
    }
}