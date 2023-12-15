<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    
    // #[Route('/', name: 'product', defaults: ['_format' => 'html'], methods: ['GET','POST'])]

    // public function productList(ProductService $productService): Response
    // {    
    //     return $this->render('index/index.html.twig', [
    //         'product' => $productService->getAllProducts(),
    //     ]);      
    // }
}