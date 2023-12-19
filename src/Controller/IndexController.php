<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\ProductService;


class IndexController extends AbstractController
{

    #[Route('/products/{page?1}', name: 'product', defaults: ['_format' => 'html'], methods: ['GET','POST'])]
    public function productList(int $page, ProductService $productService): Response
    {   
        //dd($productService->getAllProducts($page)); 
        return $this->render('index/index.html.twig', [
            'paginator' => $productService->getAllProducts($page),
        ]);      
    }
}