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

class StockService{

    public function __construct(
        public readonly ProductRepository $productRepository,
        )
    {

    }

    public function decrease(Product $product, int $qty):void
    {
        if (0 === $product->getStock()) {
            throw new \App\Exception\ProductStockDepletedException();
        }
        $product->setStock($product->getStock() - $qty);
        $this->productRepository->save($product);
    }

    public function increase(Product $product, int $qty)
    {
        $product->setStock($product->getStock() + $qty);
        $this->productRepository->save($product);
    }


}