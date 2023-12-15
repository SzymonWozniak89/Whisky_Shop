<?php
namespace App\Service;

use App\Repository\ProductRepository;

class ProductService{
    public function __construct(
        public readonly ProductRepository $productRepository,
        ){

    }

    public function getAllProducts()
    {
        return $this->productRepository->findAll();
    }
}