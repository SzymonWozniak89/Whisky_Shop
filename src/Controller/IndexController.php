<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductService;


class IndexController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->redirectToRoute('product');
    }


    #[Route('/products/{page?1}', name: 'product', defaults: ['_format' => 'html'], methods: ['GET','POST'])]
    public function productList(int $page, ProductService $productService): Response
    {
        //dd($productService->getAllProducts($page)); 
        return $this->render('index/index.html.twig', [
            'paginator' => $productService->getAllProducts($page),
        ]);      
    }
}
