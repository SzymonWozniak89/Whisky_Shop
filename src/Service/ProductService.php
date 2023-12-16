<?php
namespace App\Service;

use App\Repository\ProductRepository;

class ProductService{
    public function __construct(
        public readonly ProductRepository $productRepository,
        ){

    }

    public function getAllProducts(int $page)
    {
        return $this->productRepository->getPaginated($page);
    }
}